<?php

/**
 * @param $config
 * @return array
 * @throws Exception
 */
function execCurl($config)
{
    $retVal = [
        'success' => false,
        'result' => null,
        'code' => 0,
        'errorMessage' => '',
        'url' => '',
        'rawResponse' => '',
        'rawRequestBody' => '',
        'rawRequestHeader' => '',
    ];

    if (!isset($config['url'])) {
        throw new Exception('Please provide URL in config');
    }

    $curlHeaders = [];

    if (isset($config['headers'])) {
        $curlHeaders = $config['headers'];
    }

    if (isset($config['preloadConfig'])) {
        if (isset($config['preloadConfig']['contentType'])) {
            switch ($config['preloadConfig']['contentType']) {
                case 'json':
                    $curlHeaders['Content-Type'] = 'application/json';
                    break;
            }
        }
    }

    $sendHeaders = [];
    if (count($curlHeaders)) {
        foreach ($curlHeaders as $key => $val) {
            $sendHeaders[] = "{$key}: {$val}";
        }
    }
    if (isset($config['showURL'])) {
        if ($config['showURL'] == true) {
            $retVal['url'] = $config['url'];
        }
    }
    if (isset($config['showRawRequestHeader'])) {
        if ($config['showRawRequestHeader'] == true) {
            $retVal['rawRequestHeader'] = $sendHeaders;
        }
    }
    if (isset($config['showRawRequestBody'])) {
        if ($config['showRawRequestBody'] == true) {
            $retVal['rawRequestBody'] = @$config['data'];
        }
    }

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $config['url'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_TIMEOUT => isset($config['timeout']) ? $config['timeout'] : 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => isset($config['method']) ? strtoupper($config['method']) : 'GET',
        CURLOPT_POSTFIELDS => isset($config['body']) ? $config['body'] : null,
        CURLOPT_HTTPHEADER => $sendHeaders,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    /* execute request */
    $result = curl_exec($ch);
    $curlErrorNo = curl_errno($ch);

    if (isset($config['showRawResponse'])) {
        if ($config['showRawResponse'] === true) {
            $retVal['rawResponse'] = $result;
        }
    }

    //    echo '<pre>' . var_export($result, true) . '</pre>';

    /* close cURL resource */
    curl_close($ch);

    if ($curlErrorNo === 0) {
        $retVal['success'] = true;

        if (isset($config['resultType'])) {
            if (!empty($result)) {
                switch ($config['resultType']) {
                    case 'json':
                        $resultJSON = json_decode($result, true);

                        if (json_last_error() == JSON_ERROR_NONE) {
                            $retVal['result'] = $resultJSON;
                        } else {
                            $retVal['code'] = 50;
                            $retVal['success'] = false;
                            $retVal['errorMessage'] = 'Error parsing response';
                        }

                        break;
                    default:
                        $retVal['result'] = $result;
                        break;
                }
            } else {
                $retVal['result'] = $result;
            }
        }
    } else {
        switch ($curlErrorNo) {
            case 7:
                $retVal['code'] = 1;
                $retVal['errorMessage'] = "Couldn't Connect";
                break;
            case 28:
                $retVal['code'] = 2;
                $retVal['errorMessage'] = 'Timeout';
                break;
            case 35:
                $retVal['code'] = 3;
                $retVal['errorMessage'] = 'SSL Connect Error';
                break;
            case 47:
                $retVal['code'] = 4;
                $retVal['errorMessage'] = 'Too many redirects';
                break;
        }
    }

    return $retVal;
}

function getJWTToken()
{
    $retVal = [
        'success' => false,
        'token' => null,
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => RSUDPM_API_BASE_URL . '/auth/jwt',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode([
            'username' => 'epasien',
            'password' => 'Ji3,xU0*'
        ]),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json'
        ],
    ]);

    /* execute request */
    $result = curl_exec($ch);

    $curlErrorNo = curl_errno($ch);

    /* close cURL resource */
    curl_close($ch);

    if ($curlErrorNo === 0) {
        if (!empty($result)) {
            $resultJSON = json_decode($result, true);

            if (json_last_error() == JSON_ERROR_NONE) {
                if ($resultJSON['metadata']['responCode'] == 200) {
                    $retVal['success'] = true;
                    $retVal['token'] = $resultJSON['response']['token'];
                }
            }
        }
    }

    return $retVal;
}

function execCurlWithToken($config)
{
    $try = 0;
    $successRequest = false;
    $tokenString = '';
    $retVal = [
        'success' => false,
        'result' => null,
        'code' => 0,
        'errorMessage' => '',
        'rawResponse' => ''
    ];

    $tokenResult = getJWTToken();

    if ($tokenResult['success']) {
        $tokenString = $tokenResult['token'];

        $config['headers']['X-Token'] = $tokenString;

        $retVal = execCurl($config);

    } else {
        $retVal['code'] = 5;
        $retVal['errorMessage'] = 'Could not get token';
    }

    return $retVal;
}