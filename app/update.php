<?php
/**
 * FlyingSky's Robots
 * 2c30e5
 * 
 * 上报消息处理
 */

/* 上传数据库 */
mysqli_query(
    $DB,
    "insert into `2c30e5` (`post_type`, `input`) VALUES ('".$input['post_type']."', '".addslashes(json_encode($input))."') "
);

/* 是消息 */
if ($input['post_type'] == "message"):
    if ($input['message_type'] == 'private'):
        //私聊消息

        if ($input['user_id'] == 1234567890 && $input['raw_message'] == '重新加载') {
            $CQ->sendPrivateMsg($input['user_id'], 'OK');
            $CQ->restartPlugin();
            exit();
        }

        $AI = new Smilearth();
        $AI->setProfile(DIR.'data/person/'.$input['user_id'].'.json');
        $AI->setUpdateInformation($input['sender']);
        $AI->init();
        $AI->input($input['message']);
        $AI->save();

        //$CQ->sendPrivateMsg($input['user_id'], time());
        foreach ($AI->output as $msg) {
            $CQ->sendPrivateMsg($input['user_id'], $msg);
        }
        //$CQ->sendPrivateMsg($input['user_id'], $AI->output);
        //$CQ->sendPrivateMsg($input['user_id'], json_encode(Smilearth_Lib_langid::detect($input['raw_message'])));
        //$CQ->sendPrivateMsg($input['user_id'],'OK了');
        //if ($AI->error) $CQ->sendPrivateMsg($input['user_id'], $AI->error);

    endif;
    if ($input['message_type'] == 'group'):
        //群聊消息

        if ($input['group_id'] == 1234567890) {
            //$CQ->sendGroupMsg(1234567890, '嗯');
            $reply = file_get_contents(
                'https://host.domain.ltd'.
                '/path/to/Forward.php'.
                '?input='.
                base64_encode(
                    'sendMessage?chat_id=1234567890&text='.
                    urlencode(
                        '<b>'.$input['sender']['nickname'].'</b>'."\n".$input['raw_message']
                    ).'&parse_mode=HTML'
                )
            );
            //$CQ->sendPrivateMsg(1234567890, $reply);
        }

    endif;

    //所有消息

endif;