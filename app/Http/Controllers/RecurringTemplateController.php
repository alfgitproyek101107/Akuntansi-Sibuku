<?php

namespace App\Http\Controllers;

use App\Models\RecurringTemplate;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RecurringTemplateController extends Controller
{
    public function index()
    {
        $templates = Auth::user()->recurringTemplates()
            ->with(['account', 'category'])
            ->orderBy('next_date', 'asc')
            ->get();
        return view('recurring_templates.index', compact('templates'));
    }

    public function create()
    {
        $accounts = Auth::user()->accounts;
        $categories = Auth::user()->categories;
        return view('recurring_templates.create', compact('accounts', 'categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'frequency' => 'required|in:daily,weekly,biweekly,monthly,quarterly,yearly',
            'next_date' => 'required|date',
            'type' => 'required|in:income,expense',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify ownership
        $account = Account::find($request->account_id);
        $category = Category::find($request->category_id);
        if ($account->user_id !== Auth::id() || $category->user_id !== Auth::id()) {
            abort(403);
        }

        Auth::user()->recurringTemplates()->create([
            'name' => $request->name,
            'account_id' => $request->account_id,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'frequency' => $request->frequency,
            'next_date' => $request->next_date,
            'type' => $request->type,
            'branch_id' => session('active_branch') ?? Auth::user()->branch_id ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('recurring-templates.index')->with('success', 'Recurring template created successfully.');
    }

    public function show($id)
    {
        $recurringTemplate = Auth::user()->recurringTemplates()->findOrFail($id);
        return view('recurring_templates.show', compact('recurringTemplate'));
    }

    public function edit($id)
    {
        $recurringTemplate = Auth::user()->recurringTemplates()->findOrFail($id);
        $accounts = Auth::user()->accounts;
        $categories = Auth::user()->categories;
        return view('recurring_templates.edit', compact('recurringTemplate', 'accounts', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $recurringTemplate = Auth::user()->recurringTemplates()->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'frequency' => 'required|in:daily,weekly,biweekly,monthly,quarterly,yearly',
            'next_date' => 'required|date',
            'type' => 'required|in:income,expense',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify ownership
        $account = Account::find($request->account_id);
        $category = Category::find($request->category_id);
        if ($account->user_id !== Auth::id() || $category->user_id !== Auth::id()) {
            abort(403);
        }

        $recurringTemplate->update([
            'name' => $request->name,
            'account_id' => $request->account_id,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'frequency' => $request->frequency,
            'next_date' => $request->next_date,
            'type' => $request->type,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('recurring-templates.index')->with('success', 'Recurring template updated successfully.');
    }

    public function destroy($id)
    {
        $recurringTemplate = Auth::user()->recurringTemplates()->findOrFail($id);

        $recurringTemplate->delete();

        return redirect()->route('recurring-templates.index')->with('success', 'Recurring template deleted successfully.');
    }

    /**
     * Manually execute a recurring template to create a transaction
     */
    public function execute($id)
    {
        $recurringTemplate = Auth::user()->recurringTemplates()->findOrFail($id);

        // Check if template is active
        if (!$recurringTemplate->is_active) {
            return redirect()->back()->with('error', 'Cannot execute inactive template.');
        }

        // Check if template is due for execution
        $today = now()->toDateString();
        if ($recurringTemplate->next_date->toDateString() > $today) {
            return redirect()->back()->with('error', 'Template is not yet due for execution.');
        }

        DB::transaction(function () use ($recurringTemplate) {
            // Create transaction
            $transaction = Transaction::create([
                'user_id' => $recurringTemplate->user_id,
                'branch_id' => $recurringTemplate->branch_id,
                'account_id' => $recurringTemplate->account_id,
                'category_id' => $recurringTemplate->category_id,
                'amount' => $recurringTemplate->amount,
                'description' => $recurringTemplate->description,
                'date' => $recurringTemplate->next_date,
                'type' => $recurringTemplate->type,
            ]);

            // Update account balance
            if ($recurringTemplate->type === 'income') {
                $recurringTemplate->account->increment('balance', $recurringTemplate->amount);
            } else {
                $recurringTemplate->account->decrement('balance', $recurringTemplate->amount);
            }

            // Calculate next date
            $nextDate = $this->calculateNextDate($recurringTemplate->next_date, $recurringTemplate->frequency);

            // Update template
            $recurringTemplate->update([
                'next_date' => $nextDate,
                'last_executed_at' => now(),
            ]);
        });

        return redirect()->back()->with('success', 'Template executed successfully. Next execution: ' . $recurringTemplate->fresh()->next_date->format('d M Y'));
    }

    /**
     * Toggle active status of a recurring template
     */
    public function toggle($id)
    {
        $recurringTemplate = Auth::user()->recurringTemplates()->findOrFail($id);

        $recurringTemplate->update([
            'is_active' => !$recurringTemplate->is_active
        ]);

        $status = $recurringTemplate->is_active ? 'activated' : 'deactivated';

        return redirect()->back()->with('success', "Template {$status} successfully.");
    }

    private function calculateNextDate($currentDate, string $frequency)
    {
        $date = \Carbon\Carbon::parse($currentDate);

        switch ($frequency) {
            case 'daily':
                return $date->addDay();
            case 'weekly':
                return $date->addWeek();
            case 'biweekly':
                return $date->addWeeks(2);
            case 'monthly':
                return $date->addMonth();
            case 'quarterly':
                return $date->addMonths(3);
            case 'yearly':
                return $date->addYear();
            default:
                return $date->addMonth(); // Default to monthly
        }
    }
}
