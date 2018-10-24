<?php

namespace App\Http\Services;

use App\Code;

class ProposalCodeService
{
    /**
     * @param $requested_data
     *
     * @return mixed
     */
    public function createCode($requested_data)
    {
        $requested_data['sales_agent'] = $requested_data['user']->full_name;
        $requested_data ['user_id']    = $requested_data['user']->id;

        $proposal_type           = $this->getFirstSecChar($requested_data['proposal_type']);
        $technical_approval      = $this->getFirstSecChar($requested_data['technical_approval']); //technical_approval comes from users where auth()->user()->role_id = 2
        $sales_agent             = $this->getFirstSecChar($requested_data['sales_agent']);
        $client_source           = $this->getFirstSecChar($requested_data['client_source']);
        $requested_data ['code'] = $proposal_type . $technical_approval . '-' . $requested_data['proposal_number'] . '-' . $client_source . $sales_agent;

        return Code::create($requested_data);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function showCode($id)
    {
        return Code::find($id);
    }

    /**
     * @param $user
     *
     * @return mixed
     */
    public function listCodes($user)
    {
        return Code::where('user_id', $user->id)->select('code')->get()->toArray();
    }

    /**
     * @param $string
     *
     * @return string
     */
    public function getFirstSecChar($string)
    {
        list($first_char, $second_char) = explode(' ', $string);
        $first_char  = ucfirst($first_char[0]);
        $second_char = ucfirst($second_char[0]);
        return $first_char . $second_char;
    }
}
