<?php
// 69 ATMを作成しよう
// コマンドラインから実行すること
// 要件定義
// ・残額、入金、引き出しの機能を実装
// 実際にATMに必要な機能をリストアップして、ご自由に開発してみてください！

class ATM {

    private const MENU_NUMBER = array(1,2,3);
    private const RETRY_NUMBER = array(1,2);
    private const DEPOSIT = 1;
    private const ADD = 2;
    private const WITHDRAW = 3;
    
    public $cash;
    private $cash_total = 0;
    private $deposit;
    private $choosed_menu_number;


    public function showCashTotal() {
        return $this->cash_total;
    }

    public function addCash($cash) {
        if($cash < 1000) {
            echo 'ご入金は、1000円以上のご入金でお願い致します。' ."\n";
            return;
        }
        $this->cash_total = $this->cash_total + $cash;
        echo '入金後の残高'.$this->cash_total.'円' ."\n";
    }

    public function withDrawCash($cash) {
        if($cash < 1000) {
            echo 'ご出金は1000円以上でお願い致します' ."\n";
            return;
        }
        $deposit = $this->cash_total - $cash;
        if($deposit < 0) {
            echo '残高が不足しています。' ."\n";
            return;
        }
        $this->cash_total = $deposit;
        echo '出金後の残高'.$this->cash_total.'円' ."\n";
    }


    // 渡された値のチェック
    public function inputCode(){
        $ans = trim(fgets(STDIN));
        $checked_number = $this->checkInputCode($ans);
        if($checked_number === false) {
            return $this->inputCode();
        }
        return $checked_number;
    }

    // 入力された値のバリデーション
    public function checkInputCode($ans) {
        if($ans === '') {
            echo '値を入力してください';
            return false;
        }
        if(!is_numeric($ans)) {
            echo '数字を入力してください';
            return false;
        }
        if(!in_array($ans,self::MENU_NUMBER)) {
            echo '１から３で入力してください。（１：残高照会　２：引き出し　３：お預かり）';
            return false;
        }
        return $ans;
    }

    // 入力された値のバリデーション(リトライ)
    public function checkRetryCode($ans) {
        if($ans === '') {
            echo '値を入力してください' ."\n" ;
            return false;
        }
        if(!is_numeric($ans)) {
            echo '数字を入力してください' ."\n";
            return false;
        }
        if(!in_array($ans,self::RETRY_NUMBER)) {
            echo '続けるは[１]を,終了するは[２]を押下してください' ."\n";
            return false;
        }
        return $ans;
    }


    public function showCashTotalMenu(){
        echo '現在の残高：'.$this->showCashTotal().'円' ."\n";
    }

    public function withDrawMenu() {
        echo '引き出し金額を入力してください' . "\n";
        echo '現在の残高：'.$this->showCashTotal().'円' ."\n";

        $money = trim(fgets(STDIN));
        $this->withDrawCash($money);
    }

    public function addCashMenu() {
        echo 'お預け金額を入力してください';
        echo '現在の残高：'.$this->showCashTotal().'円' ."\n";

        $money = trim(fgets(STDIN));
        $this->addCash($money);
    }

    // 返ってきた値で、メニュー（残高照会・入金・出金）を選ぶ
    public function selectMenu($number) {
        switch($number) {
            case self::DEPOSIT:
                $this->showCashTotalMenu();
            break;
            case self::ADD:
                $this->withDrawMenu();
            break;
            case self::WITHDRAW:
                $this->addCashMenu();
            break;
        }
    }

    public function retry() {
        echo '処理を続けますか？ 続ける：１ 終了する：２' ."\n";
        $ans = trim(fgets(STDIN));
        $checked_number = $this->checkRetryCode($ans);
        if($checked_number === false) {
            return $this->retry();
        }
        return $checked_number;
    }

    public function startAtm() {
        echo "ATMマシーンです。" ."\n";
        echo "残高照会は1を、引き出しは2を、お預かりは3を押してください。" ."\n";
        $choosed_menu_number = $this->inputCode();
        $this->selectMenu($choosed_menu_number);

        $retry_number = $this->retry();
        if($retry_number == self::RETRY_NUMBER[0]) {
            return $this->startAtm();
        }else if($retry_number == self::RETRY_NUMBER[1]) {
            echo 'ありがとうございました。';
            exit;
        }
    }
}


$atm_user = new ATM();
$atm_user->startAtm();



?>