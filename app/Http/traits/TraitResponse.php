<?php

namespace App\Http\traits;

use Illuminate\Support\Facades\Response as FunctionType;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

trait TraitResponse
{

    public function ResponseSuccess(String $message, $data)
    {
        return FunctionType::json(
            [
                "status" => true,
                "message" => $message,
                "data" => $data
            ],
            HttpFoundationResponse::HTTP_CREATED,
        );
    }
    public function ResponseUnauthorize(String $message)
    {
        return FunctionType::json(
            [
                "status" => false,
                "message" => $message,
            ],
            HttpFoundationResponse::HTTP_UNAUTHORIZED,
        );
    }
    public function ResponseERROR(String $message)
    {
        return FunctionType::json(
            [
                "status" => false,
                "message" => $message,
            ],
            HttpFoundationResponse::HTTP_BAD_REQUEST,
        );
    }

    public function ResponseServerError(String $message, $reason)
    {
        return FunctionType::json(
            [
                "status" => false,
                "message" => $message,
                "reason" => $reason
            ],
            HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR,
        );
    }
    public function ResponseOk(String $message, array $data)
    {
        return FunctionType::json(
            [
                "status" => true,
                "message" => $message,
                "data" => $data
            ],
            HttpFoundationResponse::HTTP_OK,
        );
    }
}
