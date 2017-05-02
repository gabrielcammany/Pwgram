<?php
/**
 * Created by PhpStorm.
 * User: Xps_Sam
 * Date: 17/04/2017
 * Time: 18:37
 */

namespace PwGram\Model;

use PDO;
use \Datetime;

class Notifications
{
    private $request;
    private $app;

    public function __construct($request,$app)
    {
        $this->request = $request;
        $this->app = $app;
        return $this;
    }

    public function getNumber(){
        $id = $this->app['session']->get('id');
        $db = new \PDO('mysql:host=localhost;dbname=pwgram', "root", "gabriel");
        $stmt = $db->prepare('SELECT COUNT(id) FROM notification WHERE user_id = ? AND seen_by_user=0 AND NOT user_fired_event=?;');
        $stmt->bindParam(1, $id , \PDO::PARAM_STR);
        $stmt->bindParam(2, $id , \PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function setSeen(){
        if(isset($_POST['index'])){
            try {
                $id = $_POST['index'];
                $db = new \PDO('mysql:host=localhost;dbname=pwgram', "root", "gabriel");
                $stmt = $db->prepare('UPDATE notification SET seen_by_user =1 WHERE id=?;');
                $stmt->bindParam(1, $id, \PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->rowCount();
            }catch(\Exception $e){
                $result = -1;
            }
            return $result;
        }
    }

    public function getNotifications()
    {
        $id = $this->app['session']->get('id');
        if($_POST['dropdown'] == "1"){
            $db = new \PDO('mysql:host=localhost;dbname=pwgram', "root", "gabriel");
            $stmt = $db->prepare('SELECT * FROM notification WHERE user_id = ? AND seen_by_user=0 AND NOT user_fired_event=? ORDER BY created_at DESC LIMIT 4 ');
            $stmt->bindParam(1, $id , \PDO::PARAM_STR);
            $stmt->bindParam(2, $id , \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            for($i = 0;$i<count($result);$i++){
                $stmt = $db->prepare('SELECT username FROM user WHERE id = ?');
                $stmt->bindParam(1, $result[$i]["user_fired_event"] , \PDO::PARAM_STR);
                $stmt->execute();
                $result2 = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $result[$i]["user_fired_event"] = $result2[0]["username"];
                $stmt = $db->prepare('SELECT title FROM image WHERE id = ?');
                $stmt->bindParam(1, $result[$i]["post_id"] , \PDO::PARAM_STR);
                $stmt->execute();
                $result2 = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $result[$i]["post_id"] = $result[$i]["post_id"]."_".$result2[0]["title"];
            }
        }else{
            $db = new \PDO('mysql:host=localhost;dbname=pwgram', "root", "gabriel");
            $stmt = $db->prepare('SELECT * FROM notification WHERE user_id = ? AND seen_by_user=0 AND NOT user_fired_event=? ORDER BY created_at DESC');
            $stmt->bindParam(1, $id , \PDO::PARAM_STR);
            $stmt->bindParam(2, $id , \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            for($i = 0;$i<count($result);$i++){
                $stmt = $db->prepare('SELECT username FROM user WHERE id = ?');
                $stmt->bindParam(1, $result[$i]["user_fired_event"] , \PDO::PARAM_STR);
                $stmt->execute();
                $result2 = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $result[$i]["user_fired_event"] = $result2[0]["username"];
                $stmt = $db->prepare('SELECT title FROM image WHERE id = ?');
                $stmt->bindParam(1, $result[$i]["post_id"] , \PDO::PARAM_STR);
                $stmt->execute();
                $result2 = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $result[$i]["post_id"] = $result[$i]["post_id"]."_".$result2[0]["title"];
            }
        }
        return json_encode($result);
    }

}
