<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use DateTime;
use Illuminate\Foundation\Http\FormRequest;

class TopSellersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function getBegin()
    {
        return (new DateTime)->setTimestamp($this->input('end', Carbon::now()->subHours(24)->timestamp));
    }

    public function getEnd()
    {
        return (new DateTime)->setTimestamp($this->input('begin', Carbon::now()->timestamp));
    }

    public function getPage()
    {
        return $this->input('page', 1);
    }

    public function getLimit()
    {
        return $this->input('limit', 15);
    }
}
