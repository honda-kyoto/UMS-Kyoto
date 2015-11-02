#!/bin/sh
 
#マウント先
WINDOWS_SHARE='//192.168.154.1/share'
#マウント先ユーザ名
SHARE_USER='k-shibuta'
#マウント先パスワード
SHARE_PASS='kazu1229'
#マウントディレクトリ
MOUNT_POINT='/var/www/phplib/card'
#マウント確認用ファイル
MOUNT_TEST_FILE="${MOUNT_POINT}/mount.txt" 
TEST_LOCAL_USER='root'
 
#ファイルの確認
if [ ! -e ${MOUNT_TEST_FILE}  ]
then
    #ファイルが無い場合、マウントする
    echo "${WINDOWS_SHARE} is not mounted." 
    echo "try mount ..." 
    #マウント実行
    mount -t cifs -o uid=48,gid=48,user=$SHARE_USER,password=$SHARE_PASS,rw,file_mode=0777,dir_mode=0777 $WINDOWS_SHARE $MOUNT_POINT
    if [ $? -ne 0 ]
    then
        echo "mount ${WINDOWS_SHARE} faield!" >&2
    else
        echo "mount ${WINDOWS_SHARE} succeeded." 
        /usr/bin/sudo -u $TEST_LOCAL_USER /bin/touch $MOUNT_TEST_FILE > /dev/null 2>&1
    fi
else
    #ファイルが有る場合、何もしない
    echo "${WINDOWS_SHARE} is already mounted." 
fi