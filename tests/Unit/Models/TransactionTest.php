<?php

namespace Tests\Unit\Models;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Account;
use App\Models\Category;
use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $branch1;
    protected $branch2;
    protected $account1;
    protected $account2;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::factory()->create();

        // Create branches
        $this->branch1 = Branch::factory()->create(['name' => 'Branch 1']);
        $this->branch2 = Branch::factory()->create(['name' => 'Branch 2']);

        // Create accounts for different branches
        $this->account1 = Account::factory()->create([
            'user_id' => $this->user->id,
            'branch_id' => $this->branch1->id,
            'name' => 'Account Branch 1'
        ]);

        $this->account2 = Account::factory()->create([
            'user_id' => $this->user->id,
            'branch_id' => $this->branch2->id,
            'name' => 'Account Branch 2'
        ]);

        // Create category
        $this->category = Category::factory()->create([
            'user_id' => $this->user->id,
            'branch_id' => $this->branch1->id,
            'name' => 'Test Category',
            'type' => 'income'
        ]);
    }

    public function test_branch_scope_filters_transactions_by_active_branch()
    {
        // Create transactions for different branches
        $transaction1 = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'account_id' => $this->account1->id,
            'category_id' => $this->category->id,
            'branch_id' => $this->branch1->id,
            'amount' => 100.00,
            'type' => 'income'
        ]);

        $transaction2 = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'account_id' => $this->account2->id,
            'category_id' => $this->category->id,
            'branch_id' => $this->branch2->id,
            'amount' => 200.00,
            'type' => 'income'
        ]);

        // Without active branch, should return all (super admin behavior)
        $this->actingAs($this->user);
        $allTransactions = Transaction::all();
        $this->assertCount(2, $allTransactions);

        // Set active branch to branch1
        session(['active_branch' => $this->branch1->id]);

        $filteredTransactions = Transaction::all();
        $this->assertCount(1, $filteredTransactions);
        $this->assertEquals($transaction1->id, $filteredTransactions->first()->id);
    }

    public function test_transaction_belongs_to_correct_relationships()
    {
        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'account_id' => $this->account1->id,
            'category_id' => $this->category->id,
            'branch_id' => $this->branch1->id,
            'amount' => 100.00,
            'type' => 'income'
        ]);

        $this->assertInstanceOf(User::class, $transaction->user);
        $this->assertInstanceOf(Account::class, $transaction->account);
        $this->assertInstanceOf(Category::class, $transaction->category);
        $this->assertInstanceOf(Branch::class, $transaction->branch);
        $this->assertEquals($this->user->id, $transaction->user->id);
        $this->assertEquals($this->account1->id, $transaction->account->id);
        $this->assertEquals($this->category->id, $transaction->category->id);
        $this->assertEquals($this->branch1->id, $transaction->branch->id);
    }

    public function test_transaction_casts_work_correctly()
    {
        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'account_id' => $this->account1->id,
            'category_id' => $this->category->id,
            'branch_id' => $this->branch1->id,
            'amount' => 100.50,
            'tax_rate' => 10.00,
            'tax_amount' => 10.05,
            'date' => '2024-01-15',
            'type' => 'income'
        ]);

        $this->assertIsFloat($transaction->amount);
        $this->assertIsFloat($transaction->tax_rate);
        $this->assertIsFloat($transaction->tax_amount);
        $this->assertEquals(100.50, $transaction->amount);
        $this->assertEquals(10.00, $transaction->tax_rate);
        $this->assertEquals(10.05, $transaction->tax_amount);
        $this->assertInstanceOf(\Carbon\Carbon::class, $transaction->date);
    }

    public function test_transaction_fillable_attributes()
    {
        $fillable = [
            'user_id',
            'account_id',
            'category_id',
            'amount',
            'description',
            'date',
            'type',
            'transfer_id',
            'reconciled',
            'product_id',
            'tax_rate',
            'tax_amount',
            'branch_id',
            'status',
            'approved_by',
            'approved_at'
        ];

        $this->assertEquals($fillable, (new Transaction)->getFillable());
    }
}