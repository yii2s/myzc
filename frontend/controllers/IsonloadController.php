<?php
namespace frontend\controllers;
use common\models\LoginForm;
use Yii;
use yii\base\InlineAction;
use yii\helpers\Url;
use yii\web\Controller;
use frontend\models\Sort;
use frontend\models\Member;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\controllers\SortController;

/**
 * Site controller
 */
class IsonloadController extends SortController
{
	/**
	*���session�����phone��member_id 
    *���������Щ˵����¼����
    *������phone����ȡopenid�����member���Ƿ����openid
    *   ����openid�����phone��
    *       ������phone������ע��ҳ�󶨣��󶨺��������session
    *       ����phone����phone��member_id����session
    *   ������openid����openid����member��,�����ҳ�棬�󶨺��������session  
    *
    */
	public function beforeAction($action)
	{
        $session = Yii::$app->session;
		if(empty($session['id']) || empty($session['live'])){
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
	        if(strpos($user_agent, 'MicroMessenger') == true){
                $redirect_url = $_SERVER["REQUEST_URI"];
                if(empty($session['openid'])){
                    require_once(Yii::getAlias('@vendor') . "/payment/wxpay/lib/WxPay.JsApiPay.php");
                    $tools = new \JsApiPay();
                    $session->set('openid', $tools->GetOpenid());
                }
	            $member = Member::find()->where('openid=:openid',[':openid'=>$session['openid']])->one();
                if(empty($member)){
                    Header("Location:".URL::to(['register/register']).'?link='.$redirect_url);
                    exit();
                }else{
					if (!empty($member['phone'])) {
						$session->set('live', $member['phone']);
					}
                    $session->set('id', $member['id']);
                    $lifeTime = 3600*6;  // ����6Сʱ 
                    session_set_cookie_params($lifeTime); 
                }
	        }else{
	            echo '�뵽΢�ſͻ��˲鿴';
	            exit;
	        }
		}
        return true;
	}

    //���session�����phone��member_id 
    /**
    *���������Щ˵����¼����
    *������phone����ȡopenid�����member���Ƿ����openid
    *   ����openid�����phone��
    *       ������phone������ע��ҳ�󶨣��󶨺��������session
    *       ����phone����phone��member_id����session
    *   ������openid����openid����member��,�����ҳ�棬�󶨺��������session  
    *
    */

    // public function Getopenid(){
    //     $user_agent = $_SERVER['HTTP_USER_AGENT'];
    //     //if(strpos($user_agent, 'MicroMessenger') == true){
    //         require_once(Yii::getAlias('@vendor') . "/payment/wxpay/lib/WxPay.JsApiPay.php");
    //         $tools = new \JsApiPay();
    //         $GLOBALS['_openid']= $tools->GetOpenid();
    //         //yii::info('�뵽΢�ſͻ��˲鿴');
    //         //var_dump($_openid);exit;
    //     // }else{
    //     //     yii::info('�뵽΢�ſͻ��˲鿴');
    //     //     exit;
    //     // }
    // }
}
   