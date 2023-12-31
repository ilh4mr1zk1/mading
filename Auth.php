<?php

/**
 * Class Auth untuk melakukan login dan registrasi user baru
 */
class Auth {
    /**
     * @var
     * Menyimpan Koneksi database
     */
    private $db;

    /**
     * @var
     * Menyimpan Error Message
     */
    private $error;

    /**
     * @var
     * Menyimpan Email
     */
    private $emailUser;

    /**
     * @var
     * Menyimpan Nama
     */
    private $nameUser;

    /**
     * @var
     * Menyimpan Kode Message
     */
    private $kode;

    /**
     * @param $db_conn
     * Contructor untuk class Auth, membutuhkan satu parameter yaitu koneksi ke database
     */
    public function __construct($db_conn) {
        $this->db = $db_conn;

        // Mulai session
        session_start();
    }
    /**
     * @param $name
     * @param $role
     * @param $password
     * @return bool

    /**
     * @param $email
     * @param $password
     * @return bool
     *
     * fungsi login user
     */
    public function login($email, $password) {

        try {
            // Ambil data dari database
            // echo $password;exit;
            $login = $this->db->prepare("
                SELECT 
                `users`.id as id_users, `users`.nama_user as nama_user, `users`.email as email, `users`.password as password, `users`.role_id as role_id,
                `role`.id as id_role, `role`.name_role as name_role
                FROM users 
                left join role 
                on `users`.role_id = `role`.id 
                WHERE email = :email  
            ");
            $login->bindParam(":email", $email);
            $login->execute();
            $data = $login->fetch();

            // Jika jumlah baris > 0
            if ($login->rowCount() > 0) {
                // jika password yang dimasukkan sesuai dengan yg ada di database
                if (password_verify($password, $data['password'])) {
                    $_SESSION['nama_user'] = $data['nama_user'];
                    $_SESSION['name_role'] = $data['name_role'];
                    return true;
                } else {    
                    // echo "Salah";exit;
                    $this->emailUser = "$email";
                    $this->error = "Wrong Password !";
                    $this->kode = 2;

                    return false;
                }

            } else {

                $this->error = "Unregistered Email !";
                $this->emailUser = "$email";
                $this->kode = 1;
                return false;
            }

        } catch (PDOException $e) {
            echo $e->getMessage();

            return false;
        }
    }

    /**
     * @return true|void
     *
     * fungsi cek login user
     */
    public function isLoggedIn() {
        // Apakah user_session sudah ada di session

        if (isset($_SESSION['nama_user']) ) {
            return true;
        }

    }

    /**
     * @return false
     *
     * fungsi ambil data user yang sudah login
     */
    public function getUser() {
        // Cek apakah sudah login
        if (!$this->isLoggedIn()) {
            return false;
        }

        try {
            // Ambil data user dari database
            $stmt = $this->db->prepare("
                SELECT 
                `users`.id as id_users, `users`.nama_user as nama_user, `users`.email as email, `users`.password as password, `users`.role_id as role_id,
                `role`.id as id_role, `role`.name_role as name_role
                FROM users 
                left join role 
                on `users`.role_id = `role`.id 
                WHERE users.id = :id AND role.id = :id_role");
            $stmt->bindParam(":id", $_SESSION['user_id'], ":role", $_SESSION['role']);
            $stmt->execute();

            return $stmt->fetch();

        } catch (PDOException $e) {
            echo $e->getMessage();

            return false;
        }

    }

    /**
     * @return true
     *
     * fungsi Logout user
     */
    public function logout() {
        // Hapus session
        session_destroy();
        // Hapus user_session
        unset($_SESSION['user_id']);

        return true;
    }

    /**
     * @return mixed
     *
     * fungsi ambil error terakhir yg disimpan di variable error
     */
    public function getLastError() {
        return $this->error;
    }

    public function getNameUser() {
        return $this->nameUser;
    }

    public function getEmailUser() {
        return $this->emailUser;
    }

    public function getCodeUser() {
        return $this->kode;
    }

    public function countDataMessage($status_approve = 'kosong') {
        try {

            if ($status_approve == 'kosong' || $status_approve == 0 || $status_approve == 1) {
                
                // echo "Masuk Ke if";
                $getDataNotif   = $this->db->prepare("SELECT * FROM message");
                // $getDataNotif->bindParam(":stat_approve", $status_approve);
                $getDataNotif->execute();
                $getDataNotif->rowCount();
                $data = $getDataNotif->fetchAll();
                $hitungDataNotif = $getDataNotif->rowCount();
                return $hitungDataNotif;

            } else if ($status_approve !== 'kosong' || $status_approve !== 0) {

                // echo "Masuk Ke else if ";

                $getDataNotif   = $this->db->prepare("SELECT * FROM message WHERE status_approve = :stat_approve ");
                $getDataNotif->bindParam(":stat_approve", $status_approve);
                $getDataNotif->execute();
                $getDataNotif->rowCount();
                $data = $getDataNotif->fetchAll();
                $hitungDataNotif = $getDataNotif->rowCount();
                return $hitungDataNotif;

            }


        } catch (Exception $e) {
            
            echo $e->getMessage();

            return false;

        }
    }

    public function getDataMessage($status_approve = 'kosong') {
        try {

            if ($status_approve == 'kosong' || $status_approve == 0 || $status_approve == 1) {
                
                // echo "Masuk Ke if";
                $getDataNotif   = $this->db->prepare("
                    SELECT message.id as message_id, message.message_title as judul_pesan, message.message_info as isi_pesan, message.status_approve as status_approve, message.user_id as user_id, users.id as id_users, users.nama_user as nama_user, users.email as email FROM message 
                    LEFT JOIN users
                    ON message.user_id = users.id");
                // $getDataNotif->bindParam(":stat_approve", $status_approve);
                $getDataNotif->execute();
                $getDataNotif->rowCount();
                $data = $getDataNotif->fetchAll();
                $hitungDataNotif = $getDataNotif->rowCount();
                // for ($i=0; $i < $hitungDataNotif; $i++) { 
                //     echo $data[$i]['message_title'] . "<br>";
                // }
                return $data;

            } else if ($status_approve !== 'kosong' || $status_approve !== 0) {

                // echo "Masuk Ke else if ";

                $getDataNotif   = $this->db->prepare("SELECT * FROM message WHERE status_approve = :stat_approve ");
                $getDataNotif->bindParam(":stat_approve", $status_approve);
                $getDataNotif->execute();
                $getDataNotif->rowCount();
                $data = $getDataNotif->fetchAll();
                $hitungDataNotif = $getDataNotif->rowCount();
                return $data;

            }


        } catch (Exception $e) {
            
            echo $e->getMessage();

            return false;

        }
    }

}