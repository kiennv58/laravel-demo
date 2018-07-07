<?php

namespace App\Http\Controllers\Api;

trait ResponseHandler {

    protected function successResponse($data, $transform = true) {
        if (is_null($data))
            $data = [];

        $response = array_merge([
            'code' => 200,
            'status' => 'success',
        ], $transform ? $this->transform($data) : $data);

        //get query log
        $this->_getQueryLog($response);

        return response()->json($response, $response['code']);
    }

    protected function notFoundResponse() {
        $response = [
            'code' => 404,
            'status' => 'error',
            'data' => 'Resource Not Found',
            'message' => 'Not Found'
        ];
        return response()->json($response, $response['code']);
    }

    public function deleteResponse() {
        $response = [
            'code' => 200,
            'status' => 'success',
            'data' => [],
            'message' => 'Resource Deleted'
        ];
        return response()->json($response, $response['code']);
    }

    public function errorResponse($data, $transform = true) {
        $response = [
            'code' => 422,
            'status' => 'error',
            'data' => $data,
            'message' => 'Unprocessable Entity'
        ];
        return response()->json($response, $response['code']);
    }

    protected function infoResponse($data) {

        $data = [
            'data' => $data
        ];

        $response = array_merge([
            'code' => 200,
            'status' => 'success'
        ], $data);
        return response()->json($response, $response['code']);
    }

    private function _getQueryLog(&$response)
    {
        $user = auth()->user();
        if (env('APP_ENV', 'production') == 'local' || $user && $user->isSuperAdmin()) {
            $renderer = \Debugbar::collect();
            $queries = array_get($renderer, 'queries', []);
            $queries['method']     = array_get($renderer, '__meta.method', 'N/a');
            $queries['uri']        = array_get($renderer, '__meta.uri', 'N/a');
            $queries['memory']     = array_get($renderer, 'memory.peak_usage_str', 'N/a');
            $queries['middleware'] = array_get($renderer, 'route.middleware', 'N/a');
            $queries['file']       = array_get($renderer, 'route.file', 'N/a');

            $statements = array_get($queries, 'statements', []);

            foreach ($statements as $key => $info) {
                $arrExcept = ['type', 'params', 'bindings', 'backtrace', 'connection', 'hints'];
                if ($info['type'] == 'explain') {
                    $arrExcept = ['sql', 'type'];
                }

                $currentState = array_except($info, $arrExcept);

                if ($info['type'] == 'explain') {
                    $beforeState = $statements[$key - 1];
                    unset($statements[$key - 1]);

                    $newState = $beforeState;
                    $newState['stt_exp'][] = $currentState;
                    $currentState = $newState;
                }

                $statements[$key] = $currentState;
            }

            $queries['statements'] = array_values($statements);
            $response['queries'] = $queries;
        }
    }
}
