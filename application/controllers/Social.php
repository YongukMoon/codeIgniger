<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Social extends MY_controller
{
    protected function social_login_url(){

		// NAVER LOGIN
		$n_client_id = "";
		$n_redirect_uri = urlencode("http://www.alcongpay.com/social/n_login");
		$n_api_url = "https://nid.naver.com/oauth2.0/authorize";
		$n_query_string = "?response_type=code&client_id=".$n_client_id."&redirect_uri=".$n_redirect_uri;

		$data['naver_url'] = $n_api_url.$n_query_string;


		// KAKAO LOGIN 
		$k_client_id = "";
		$k_redirect_uri = urlencode("http://www.alcongpay.com/social/k_login");
		$k_api_url = "https://kauth.kakao.com/oauth/authorize";
		$k_query_string = "?client_id=".$k_client_id."&redirect_uri=".$k_redirect_uri."&response_type=code";

		$data['kakao_url'] = $k_api_url.$k_query_string;

		return $data;
    }
    
	public function k_login(){

		// KAKAO LOGIN 
		define('KAKAO_CLIENT_ID', '');
		define('KAKAO_CALLBACK_URL', '');

		if (isset($_GET["code"])) { 


			//사용자 토큰 받기 
			$code = $_GET["code"]; 
			$params = sprintf( 'grant_type=authorization_code&client_id=%s&redirect_uri=%s&code=%s', KAKAO_CLIENT_ID, KAKAO_CALLBACK_URL, $code); 
			$TOKEN_API_URL = "https://kauth.kakao.com/oauth/token"; 
			$opts = array( 
				CURLOPT_URL => $TOKEN_API_URL, 
				CURLOPT_SSL_VERIFYPEER => false, 
				CURLOPT_SSLVERSION => 1, // TLS 
				CURLOPT_POST => true, 
				CURLOPT_POSTFIELDS => $params, 
				CURLOPT_RETURNTRANSFER => true, 
				CURLOPT_HEADER => false 
				); 
			$curlSession = curl_init(); 
			curl_setopt_array($curlSession, $opts); 
			$accessTokenJson = curl_exec($curlSession); 
			curl_close($curlSession); 
			$responseArr = json_decode($accessTokenJson, true); 

			$token['kakao_access_token']=$responseArr['access_token'];
			$token['kakao_refresh_token']=$responseArr['refresh_token'];
			$this->set_session($token);


			//사용자 정보 가져오기 
			$USER_API_URL= "https://kapi.kakao.com/v2/user/me"; 
			$opts = array( 
				CURLOPT_URL => $USER_API_URL, 
				CURLOPT_SSL_VERIFYPEER => false, 
				CURLOPT_SSLVERSION => 1, 
				CURLOPT_POST => true, 
				CURLOPT_POSTFIELDS => false, 
				CURLOPT_RETURNTRANSFER => true, 
				CURLOPT_HTTPHEADER => array( 
				"Authorization: Bearer " . $responseArr['access_token'] 
				) 
			); 
			$curlSession = curl_init();
			curl_setopt_array($curlSession, $opts); 
			$accessUserJson = curl_exec($curlSession); 
			curl_close($curlSession); 
			$me_responseArr = json_decode($accessUserJson, true); 
								
			if ($me_responseArr['id']) { 
				

				// 소셜유저 등록
				$this->load->model('md_member');
				$member=$this->md_member->get_member($me_responseArr['kakao_account']['email']);

				if(!isset($member)){

					$data['m_id'] = $me_responseArr['kakao_account']['email'];
					$data['recom_id1'] = '';
					$data['recom_id2'] = '';
					$data['m_pwd'] = '';
					$data['m_tel'] = '';
					$data['m_name'] = $me_responseArr['properties']['nickname'] ?: 'Unknown';
					$data['m_grade'] = 1;
					$data['m_regdate'] = date("Y-m-d H:i:s");
					$data['m_flag'] = 'K';

					$this->md_member->get_memAdd($data);
				}


				//로그인
				$member=$this->md_member->get_member($me_responseArr['kakao_account']['email']);
				$this->set_session($member);

				header('Location: http://www.alcongpay.com/user');
		
		
			} else { 
				// 회원정보를 가져오지 못했습니다. 
			}




		}else{
			//연결 취소
			redirect('user');
		}
	}

	public function k_logout(){

		$LOGOUT_API_URL = "https://kapi.kakao.com/v1/user/logout"; 
		$opts = array( 
			CURLOPT_URL => $LOGOUT_API_URL, 
			CURLOPT_SSL_VERIFYPEER => false, 
			CURLOPT_SSLVERSION => 1, 
			CURLOPT_POST => true, 
			CURLOPT_POSTFIELDS => false, 
			CURLOPT_RETURNTRANSFER => true, 
			CURLOPT_HTTPHEADER => array( 
			"Authorization: Bearer " . $this->get_session('kakao_access_token')
			) 
		); 
		$curlSession = curl_init();
		curl_setopt_array($curlSession, $opts); 
		$accessLogoutJson = curl_exec($curlSession); 
		curl_close($curlSession); 
		$logout_responseArr = json_decode($accessLogoutJson, true);

		$this->session->sess_destroy();

		print_r($logout_responseArr);
	}

	public function k_unlink(){

		$UNLINK_API_URL = "https://kapi.kakao.com/v1/user/unlink"; 
		$opts = array( 
			CURLOPT_URL => $UNLINK_API_URL, 
			CURLOPT_SSL_VERIFYPEER => false, 
			CURLOPT_SSLVERSION => 1, 
			CURLOPT_POST => true, 
			CURLOPT_POSTFIELDS => false, 
			CURLOPT_RETURNTRANSFER => true, 
			CURLOPT_HTTPHEADER => array( 
			"Authorization: Bearer " . $this->get_session('kakao_access_token')
			) 
		); 
		$curlSession = curl_init();
		curl_setopt_array($curlSession, $opts); 
		$accessUnlinkJson = curl_exec($curlSession); 
		curl_close($curlSession); 
		$unlink_responseArr = json_decode($accessUnlinkJson, true);

		$this->session->sess_destroy();

		print_r($unlink_responseArr);
	}

	public function n_login(){

		if(isset($_GET["code"])){


			$code = $_GET["code"];
			$client_id = "";
			$client_secret = "";
			$state = $_GET["state"];
			$redirectURI = urlencode("http://www.alcongpay.com/social/n_login");
			$USER_API_URL= "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code";
			$queryString = "&client_id=".$client_id."&client_secret=".$client_secret."&redirect_uri=".$redirectURI."&code=".$code."&state=".$state;
			$is_post = false;


			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $USER_API_URL.$queryString);
			curl_setopt($ch, CURLOPT_POST, $is_post);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$headers = array();
			$response = curl_exec ($ch);
			$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close ($ch);
			
			
			if($status_code == 200) {
				$responseArr = json_decode($response, true);

				//토큰 세션에 저장
				$token['naver_access_token']=$responseArr['access_token'];
				$token['naver_refresh_token']=$responseArr['refresh_token'];
				$this->set_session($token);

				// 토큰값으로 네이버 회원정보 가져오기 
				$me_headers = array( 
					'Content-Type: application/json', 
					sprintf('Authorization: Bearer %s', 
					$responseArr['access_token']) 
				);
				

				$me_is_post = false; 
				$me_ch = curl_init(); 
				curl_setopt($me_ch, CURLOPT_URL, "https://openapi.naver.com/v1/nid/me"); 
				curl_setopt($me_ch, CURLOPT_POST, $me_is_post); 
				curl_setopt($me_ch, CURLOPT_HTTPHEADER, $me_headers); 
				curl_setopt($me_ch, CURLOPT_RETURNTRANSFER, true); 
				$me_response = curl_exec ($me_ch); 
				$me_status_code = curl_getinfo($me_ch, CURLINFO_HTTP_CODE); 
				curl_close ($me_ch); 
				$me_responseArr = json_decode($me_response, true);

				// 소셜유저 등록
				$this->load->model('md_member');
				$member=$this->md_member->get_member($me_responseArr['response']['email']);

				if(!isset($member)){

					$data['m_id'] = $me_responseArr['response']['email'];
					$data['recom_id1'] = '';
					$data['recom_id2'] = '';
					$data['m_pwd'] = '';
					$data['m_tel'] = '';
					$data['m_name'] = $me_responseArr['response']['name'] ?: 'Unknown';
					$data['m_grade'] = 1;
					$data['m_regdate'] = date("Y-m-d H:i:s");
					$data['m_flag'] = 'N';

					$this->md_member->get_memAdd($data);
				}


				//로그인
				$member=$this->md_member->get_member($me_responseArr['response']['email']);
				$this->set_session($member);

				header('Location: http://www.alcongpay.com/user');



			} else {
				echo "Error 내용:".$response;
			}


		}else{
			//연결 취소
			redirect('user');
		}
	}

	public function n_unlink(){
		define('NAVER_CLIENT_ID', '3vO7vsO8HLoDJiQFYbnv'); 
		define('NAVER_CLIENT_SECRET', 'xH49BKNXwv');

		$access_token=urlencode($this->get_session('naver_access_token'));
		

		// 네이버 접근 토큰 삭제 
		$USER_API_URL = "https://nid.naver.com/oauth2.0/token?grant_type=delete";
		$naver_curl = "&client_id=".NAVER_CLIENT_ID."&client_secret=".NAVER_CLIENT_SECRET."&access_token=".$access_token."&service_provider=NAVER"; 
		$is_post = false; 
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $USER_API_URL.$naver_curl); 
		curl_setopt($ch, CURLOPT_POST, $is_post); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		$response = curl_exec ($ch); 
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		curl_close ($ch);

		if($status_code == 200) { 


			$responseArr = json_decode($response, true); 

			$this->session->sess_destroy();

			print_r($responseArr);


			// 멤버 DB에서 회원을 탈퇴해주고 로그아웃(세션, 쿠키 삭제) 
			if ($responseArr['result'] != 'success') { 
				// 오류가 발생하였습니다. 네이버 내정보->보안설정->외부 사이트 연결에서 해당앱을 삭제하여 주십시오 
			}
			



		} else { 
			// 오류가 발생하였습니다. 네이버 내정보->보안설정->외부 사이트 연결에서 해당앱을 삭제하여 주십시오. 
		}
	}
}