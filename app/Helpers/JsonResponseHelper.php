<?php

if (!function_exists('JsonStatus')) {

	/**
	 * Status Response
	 *
	 * @param string $message
	 * @param int $status
	 * @return \Illuminate\Http\JsonResponse
	 */
	function JsonStatus($message = 'success', $status = 200)
	{
		return Response()->json([
			'status'  => $status,
			'message' => $message,
		]);
	}
}
