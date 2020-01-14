<?php
/**
 * @OA\Info(
 *   title="視頻網站API",
 *   version="1.2"
 * )
 */

/**
 * @OA\Server(
 *   url="{schema}://api.iqqtv",
 *   description="本機開發",
 *   @OA\ServerVariable(
 *       serverVariable="schema",
 *       enum={"https", "http"},
 *       default="http"
 *   )
 * )
 *
 * @OA\Server(
 *   url="{schema}://tv.f1good.com",
 *   description="測試機",
 *   @OA\ServerVariable(
 *       serverVariable="schema",
 *       enum={"https", "http"},
 *       default="http"
 *   )
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="JWT",
 *   type="apiKey",
 *   in="header",
 *   description="請輸入:Bearer {Token}",
 *   name="Authorization"
 * ),
 */
