<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\IncomeItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncomeMultiItemTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $account;
    protected $category;
    protected $products;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::factory()->create();

        // Create test account
        $this->account = Account::factory()->create([
            'user_id' => $this->user->id,
            'balance' => 1000000,
        ]);

        // Create test category
        $this->category = Category::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'income',
        ]);

        // Create test products
        $this->products = Product::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'stock_quantity' => 100,
        ]);
    }

    public function test_income_item_can_be_created()
    {
        $transaction = Transaction::create([
            'user_id' => $this->user->id,
            'account_id' => $this->account->id,
            'category_id' => $this->category->id,
            'type' => 'income',
            'amount' => 100000,
            'description' => 'Test income',
            'date' => now(),
        ]);

        $incomeItem = IncomeItem::create([
            'income_id' => $transaction->id,
            'product_id' => $this->products[0]->id,
            'quantity' => 2,
            'price' => 50000,
            'subtotal' => 100000,
        ]);

        $this->assertDatabaseHas('income_items', [
            'income_id' => $transaction->id,
            'product_id' => $this->products[0]->id,
            'quantity' => 2,
            'price' => 50000,
            'subtotal' => 100000,
        ]);
    }

    public function test_income_item_belongs_to_transaction()
    {
        $transaction = Transaction::create([
            'user_id' => $this->user->id,
            'account_id' => $this->account->id,
            'category_id' => $this->category->id,
            'type' => 'income',
            'amount' => 100000,
            'description' => 'Test income',
            'date' => now(),
        ]);

        $incomeItem = IncomeItem::create([
            'income_id' => $transaction->id,
            'product_id' => $this->products[0]->id,
            'quantity' => 2,
            'price' => 50000,
            'subtotal' => 100000,
        ]);

        $this->assertEquals($transaction->id, $incomeItem->income->id);
        $this->assertEquals($this->products[0]->id, $incomeItem->product->id);
    }

    public function test_transaction_can_have_multiple_income_items()
    {
        $transaction = Transaction::create([
            'user_id' => $this->user->id,
            'account_id' => $this->account->id,
            'category_id' => $this->category->id,
            'type' => 'income',
            'amount' => 200000,
            'description' => 'Test multi-item income',
            'date' => now(),
        ]);

        IncomeItem::create([
            'income_id' => $transaction->id,
            'product_id' => $this->products[0]->id,
            'quantity' => 2,
            'price' => 50000,
            'subtotal' => 100000,
        ]);

        IncomeItem::create([
            'income_id' => $transaction->id,
            'product_id' => $this->products[1]->id,
            'quantity' => 2,
            'price' => 50000,
            'subtotal' => 100000,
        ]);

        $this->assertCount(2, $transaction->fresh()->incomeItems);
        $this->assertEquals(200000, $transaction->amount);
    }

    public function test_income_item_subtotal_is_calculated_automatically()
    {
        $transaction = Transaction::create([
            'user_id' => $this->user->id,
            'account_id' => $this->account->id,
            'category_id' => $this->category->id,
            'type' => 'income',
            'amount' => 75000,
            'description' => 'Test subtotal calculation',
            'date' => now(),
        ]);

        $incomeItem = IncomeItem::create([
            'income_id' => $transaction->id,
            'product_id' => $this->products[0]->id,
            'quantity' => 3,
            'price' => 25000,
        ]);

        // The subtotal should be calculated automatically by the model
        $this->assertEquals(75000, $incomeItem->subtotal);
    }
}
