<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as RequestOne;

class CommonController extends Controller
{

    const AUTCH_CODE_URI = 'https://accounts.zoho.com/oauth/v2/token';
    const CREATE_DEAL_URI = 'https://www.zohoapis.com/crm/v2/Deals';
    const CREATE_TASK_URI = 'https://www.zohoapis.com/crm/v2/Tasks';

    public function index() {
        return view('common.index');
    }

    /**
     * Send request
     *
     * @return \Illuminate\Http\Response
     */
    public function sendRequest() {
        $post = request()->post();
        $result = '';
        if (!is_null($post) && !empty($post)) {
            if (!empty($post['clientId']) && !empty($post['clientSecret']) && !empty($post['code']) && !empty($post['dealName'])&& !empty($post['dealStage'])&& !empty($post['taskName'])) {
                $accessToken = RequestOne::getAccessToken(self::AUTCH_CODE_URI, 'authorization_code', $post['clientId'], $post['clientSecret'], $post['code']);
                if (!empty($accessToken)) {
                    $result = RequestOne::createDeal(self::CREATE_DEAL_URI, self::CREATE_TASK_URI, $accessToken, $post['dealStage'], $post['dealStage'], $post['taskName']);
                }
            }
            echo $result;
        } else {
            return redirect()->back();
        }
    }
}
