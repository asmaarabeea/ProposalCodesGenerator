<?php

namespace App\Http\Controllers;

use App\Http\Services\ProposalCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProposalCodeController extends Controller
{
    protected $proposal_code_service;

    /**
     * ProposalCodeController constructor.
     *
     * @param ProposalCodeService $proposal_code_service
     */
    public function __construct(ProposalCodeService $proposal_code_service)
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role_id != 1) {   // TODO move to middleware
                return response()->json("The resource you are looking for could not be found .");
            }
            return $next($request);
        });
        $this->proposal_code_service = $proposal_code_service;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        // proposal_number getting from table of sellers and it has to be auto increment key starting from 0001 as ex
        $requested_data = $request->only('proposal_type', 'technical_approval', 'proposal_number', 'client_source', 'client_name', 'proposal_date', 'proposal_value');
        $validator      = Validator::make($requested_data, [
            'proposal_type'      => 'required',
            'technical_approval' => 'required',  // |exists:users,id',
            'proposal_number'    => 'required',  // |exists:table,id',
            'client_source'      => 'required',
            'proposal_date'      => 'nullable|date'
        ]);
        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $requested_data['user'] = auth()->user();
        $this->proposal_code_service->createCode($requested_data);

        return response()->json([
            'message' => 'Code created successfully',
            "success" => 200
        ], 201);
    }


    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $code = $this->proposal_code_service->showCode($id);
        return response()->json([
            'message' => 'Code data rendered successfully',
            "success" => 200,
            "code"    => $code
        ], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function listCodes()
    {
        $codes = $this->proposal_code_service->listCodes(auth()->user());
        return response()->json([
            'message' => 'Codes rendered successfully',
            "success" => 200,
            "codes"   => $codes
        ], 200);
    }

}
