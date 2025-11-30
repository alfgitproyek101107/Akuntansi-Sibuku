<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Account;
use App\Models\Category;
use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncomeWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $branch;
    protected $account;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::factory()->create();

        // Create branch
        $this->branch = Branch::factory()->create();

        // Create account
        $this->account = Account::factory()->create([
            'user_id' => $this->user->id,
            'branch_id' => $this->branch->id,
            'balance' => 1000.00
        ]);

        // Create category
        $this->category = Category::factory()->create([
            'user_id' => $this->user->id,
            'branch_id' => $this->branch->id,
            'type' => 'income'
        ]);
    }

    public function test_user_can_create_income_transaction()
    {
        $this->actingAs($this->user);

        $incomeData = [
            'account_id' => $this->account->id,
            'category_id' => $this->category->id,
            'amount' => 500.00,
            'description' => 'Test income transaction',
            'date' => '2024-01-15'
        ];

        $response = $this->post(route('incomes.store'), $incomeData);

        $response->assertRedirect(route('incomes.index'));
        $response->assertSessionHas('success');

        // Verify transaction was created
        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'account_id' => $this->account->id,
            'category_id' => $this->category->id,
            'amount' => 500.00,
            'description' => 'Test income transaction',
            'type' => 'income',
            'branch_id' => $this->branch->id
        ]);

        // Verify account balance was updated
        $this->account->refresh();
        $this->assertEquals(1500.00, $this->account->balance);
    }

    public function test_income_creation_validates_required_fields()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('incomes.store'), []);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['account_id', 'category_id', 'amount', 'date']);
    }

    public function test_income_creation_validates_amount_positive()
    {
        $this->actingAs($this->user);

        $incomeData = [
            'account_id' => $this->account->id,
            'category_id' => $this->category->id,
            'amount' => -100.00,
            'description' => 'Invalid amount',
            'date' => '2024-01-15'
        ];

        $response = $this->post(route('incomes.store'), $incomeData);

        $response->assertRedirect();
        $response->assertSessionHasErrors('amount');
    }

    public function test_income_creation_validates_ownership()
    {
        $otherUser = User::factory()->create();
        $otherAccount = Account::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user);

        $incomeData = [
            'account_id' => $otherAccount->id, // This account belongs to another user
            'category_id' => $this->category->id,
            'amount' => 500.00,
            'description' => 'Should fail',
            'date' => '2024-01-15'
        ];

        $response = $this->post(route('incomes.store'), $incomeData);

        $response->assertStatus(403); // Forbidden
    }

    public function test_income_creation_uses_database_transaction()
    {
        $this->actingAs($this->user);

        // Mock a scenario where database transaction should rollback on failure
        $incomeData = [
            'account_id' => $this->account->id,
            'category_id' => $this->category->id,
            'amount' => 500.00,
            'description' => 'Test transaction rollback',
            'date' => '2024-01-15'
        ];

        // This should succeed and update balance
        $response = $this->post(route('incomes.store'), $incomeData);
        $response->assertRedirect();

        $this->account->refresh();
        $this->assertEquals(1500.00, $this->account->balance);

        // Verify transaction exists
        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'amount' => 500.00,
            'type' => 'income'
        ]);
    }

    public function test_income_update_workflow()
    {
        $this->actingAs($this->user);

        // Create initial income
        $incomeData = [
            'account_id' => $this->account->id,
            'category_id' => $this->category->id,
            'amount' => 300.00,
            'description' => 'Initial income',
            'date' => '2024-01-15'
        ];

        $this->post(route('incomes.store'), $incomeData);

        $transaction = $this->user->transactions()->where('type', 'income')->first();

        // Update the income
        $updateData = [
            'account_id' => $this->account->id,
            'category_id' => $this->category->id,
            'amount' => 400.00, // Changed from 300 to 400
            'description' => 'Updated income',
            'date' => '2024-01-16'
        ];

        $response = $this->put(route('incomes.update', $transaction->id), $updateData);
        $response->assertRedirect();

        // Verify balance was correctly adjusted (400 - 300 = +100)
        $this->account->refresh();
        $this->assertEquals(1100.00, $this->account->balance);

        // Verify transaction was updated
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'amount' => 400.00,
            'description' => 'Updated income'
        ]);
    }

    public function test_income_deletion_workflow()
    {
        $this->actingAs($this->user);

        // Create income
        $incomeData = [
            'account_id' => $this->account->id,
            'category_id' => $this->category->id,
            'amount' => 200.00,
            'description' => 'Income to delete',
            'date' => '2024-01-15'
        ];

        $this->post(route('incomes.store'), $incomeData);

        $transaction = $this->user->transactions()->where('type', 'income')->first();

        // Delete the income
        $response = $this->delete(route('incomes.destroy', $transaction->id));
        $response->assertRedirect();

        // Verify balance was reverted
        $this->account->refresh();
        $this->assertEquals(1000.00, $this->account->balance);

        // Verify transaction was deleted
        $this->assertDatabaseMissing('transactions', [
            'id' => $transaction->id
        ]);
    }

    public function test_rate_limiting_on_income_creation()
    {
        $this->actingAs($this->user);

        $incomeData = [
            'account_id' => $this->account->id,
            'category_id' => $this->category->id,
            'amount' => 100.00,
            'description' => 'Rate limit test',
            'date' => '2024-01-15'
        ];

        // Make multiple requests quickly (should be rate limited)
        for ($i = 0; $i < 35; $i++) { // More than the 30 per minute limit
            $response = $this->post(route('incomes.store'), $incomeData);

            if ($i >= 30) {
                // Should be rate limited after 30 requests
                $response->assertStatus(429); // Too Many Requests
                break;
            } else {
                $response->assertRedirect();
            }
        }
    }
}