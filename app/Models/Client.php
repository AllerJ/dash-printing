<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $connection = 'mysqldashboard';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'code', 'individual', 'name', 'primary_contact', 'email', 'website', 'phone', 'mobile', 'fax', 'address1',
    'address2', 'city', 'state', 'locale', 'country', 'tax_number', 'zip_code', 'currency', 'expense', 'balance', 'paid', 'skype',
    'linkedin', 'facebook', 'twitter', 'notes', 'logo', 'owner', 'slack_webhook_url', 'unsubscribed_at', 'stripe_id', 'card_brand',
    'card_last_four', 'trial_ends_at',
];
    protected $appends = ['contact_person', 'expense_cost', 'outstanding', 'map', 'maplink'];
    protected $dates   = ['deleted_at', 'created_at', 'updated_at', 'trial_ends_at'];





    public function nextCode()
    {
        $code = sprintf('%05d', 1);
        $max  = $this->whereNotNull('code')->max('id');
        if ($max > 0) {
            $row         = $this->find($max);
            $ref_number  = intval(substr($row->code, -4));
            $next_number = $ref_number + 1;
            if ($next_number < 1) {
                $next_number = 1;
            }
            $next_number = $this->codeExists($next_number);
            $code        = sprintf('%05d', $next_number);
        }
        return 'COM'. $code;
    }
    public function codeExists($next_number)
    {
        $next_number = sprintf('%05d', $next_number);
        if ($this->withTrashed()->whereCode('COM' . $next_number)->count() > 0) {
            return $this->codeExists((int) $next_number + 1);
        }
        return $next_number;
    }
}
