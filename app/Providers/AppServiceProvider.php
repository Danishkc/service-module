<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('pro_id_required_if_filled', function ($attribute, $value, $parameters, $validator) {
            $lines = $validator->getData()['lines']; // Fetch the 'lines' input from the data

            // Loop through each line item and check if pro_id is not provided when other fields are filled
            foreach ($lines as $line) {
                if (!empty($line['description']) || !empty($line['quantity']) || !empty($line['unit_price']) || !empty($line['taxes'])) {
                    if (empty($line['pro_id'])) {
                        return false; // Return false if pro_id is not provided when other fields are filled
                    }
                }
            }

            return true; // Return true if all conditions pass
        });

        Validator::extend('product_id_required_if_fields_filled', function ($attribute, $value, $parameters, $validator) {
            $lines = $validator->getData()['lines']; // Fetch the 'lines' input from the data

            // Loop through each line item and check if product_id is not provided when other fields are filled
            foreach ($lines as $line) {
                if (!empty($line['demand']) || !empty($line['done']) || !empty($line['used'])) {
                    if (empty($line['product_id'])) {
                        return false; // Return false if product_id is not provided when other fields are filled
                    }
                }
            }

            return true; // Return true if all conditions pass
        });

        Validator::extend('option_pro_id_required_if_filled', function ($attribute, $value, $parameters, $validator) {
            $lines = $validator->getData()['lines']; // Fetch the 'lines' input from the data

            // Loop through each line item and check if pro_id is not provided when other fields are filled
            foreach ($lines as $line) {
                if (!empty($line['option_description']) || !empty($line['option_quantity']) || !empty($line['option_unit_price'])) {
                    if (empty($line['option_pro_id'])) {
                        return false; // Return false if pro_id is not provided when other fields are filled
                    }
                }
            }

            return true; // Return true if all conditions pass
        });
    }
}
