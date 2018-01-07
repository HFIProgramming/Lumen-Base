<?php

if (!function_exists('JsonData')) {

	/**
	 * Status Response
	 *
	 * @param string $message
	 * @param int $status
	 * @return \Illuminate\Http\JsonResponse
	 */
	function JsonData($data, $message = 'success', $status = 200)
	{
		return Response()->json([
			'status'  => $status,
			'message' => $message,
			'data'    => $data,
		]);
	}
}