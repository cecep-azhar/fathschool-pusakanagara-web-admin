<?php

namespace Database\Seeders\Dusk;

use App\Models\Invoice;
use App\Models\TransactionType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class InvoiceTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Invoice::factory(5)->create();

        $invoices = Invoice::all();

        foreach ($invoices as $key => $invoice) {

            $num = rand(1, 3);

            for ($i = 0; $i < $num; $i++) {

                $name = Arr::random([
                    'Invoice recommendations',
                    'Freelancer invoice example',
                    'Contractual work invoice',
                    'Professional services invoice',
                    'Retail invoice',
                    'Catering invoice',
                    'Wholesale invoice',
                    'Lump-sum invoice',
                    'Benefits of invoices',
                    'How to get your invoices paid faster',
                ]);

                $invoice->items()->create([
                    'amount' => rand(1, 90),
                    'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugit autem ea voluptatem, fuga aliquam placeat. Tenetur eligendi maiores laboriosam libero, accusantium obcaecati fugit non dolores perferendis labore facere voluptatum eos!',
                ]);

                $invoice->attachments()->create([
                    'attachment_url' => 'files/sample.pdf',
                ]);
            }

            $this->createTransaction($invoice->id);
        }
    }

    /**
     * create transaction under an invoice
     */
    public function createTransaction($invoice_id)
    {
        // check income type is exist
        $exit_type = TransactionType::where('name', 'From Invoice')->first();

        if (! $exit_type) {
            $type = TransactionType::create([
                'type' => 'income',
                'name' => 'From Invoice',
            ]);
        } else {
            $type = $exit_type;
        }

        // get invoice item
        $invoice = Invoice::withSum('items', 'amount')->FindOrFail($invoice_id);
        // for description
        $single_item = $invoice->items()->first();

        $title = $invoice->title;
        $user = $invoice->user_id;
        $amount = $invoice->items_sum_amount;
        $date = $invoice->date;
        $description = $single_item ? $single_item->description : '';

        // data store after validate
        $invoice->transactions()->create([
            'transaction_no' => 'TRN_'.time(),
            'transaction_type_id' => $type->id,
            'title' => $title,
            'user_id' => $user,
            'amount' => $invoice->amount,
            'date' => Carbon::parse($date),
            'due_date' => Carbon::parse($invoice->due_date),
            'status' => Arr::random(['paid', 'unpaid']),
            'description' => $description,
            'currency' => Arr::random(['USD', 'EUR', 'GBP', 'BDT']),
        ]);

        return true;
    }
}
