<?php include("view/head.tpl") ?>
      <input type="hidden" name="user_id" id="user_id" value="">
      <input type="hidden" name="page" id="page" value="<?php echo $mgr->getRequestData('page') ?>">
      <input type="hidden" name="order" id="order" value="">
<?php if ($mgr->isUserCtrlUser()) { ?>
      <div id="topBtnArea">
        <input type="button" onclick="formSubmit('input', 'users_regist.php');" value="　　　新規登録　　　">
      </div>
<?php } ?>
      <div style="clear:both;"></div>
<?php include("view/users/med_work_regist_cond.tpl") ?>
<?php if (is_array(@$mgr->aryList)) { ?>
      <div class="resultBlock">
        <h3>■検索結果一覧</h3>
            <td align="right">表示件数：
<?php   if (count($mgr->aryList) > 0) { ?>
        <table cellspacing="0" class="listTabH">
          <tr>
            <td><?php echo $mgr->getListNavi() ?></td>
                <select name="list_max" onChange="listMax();">
                <?php echo $mgr->getSelectList('list_max') ?>
              </select></td>
          </tr>
        </table>
        <table width="100%" border="0" cellpadding="2" cellspacing="0" class="listTab">
          <tr>
            <th scope="col" width="56"><a class="sortlink" href="javascript:sort('login_id');"><img src="<?php echo $mgr->getOrderData('src', 'login_id') ?>" alt="<?php echo $mgr->getOrderData('msg', 'login_id') ?>">統合ID</a></th>
            <th scope="col" width="62"><a class="sortlink" href="javascript:sort('staffcode');"><img src="<?php echo $mgr->getOrderData('src', 'staffcode') ?>" alt="<?php echo $mgr->getOrderData('msg', 'staffcode') ?>">ｶｰﾄﾞNo.</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('belong_chg_name');"><img src="<?php echo $mgr->getOrderData('src', 'belong_chg_name') ?>" alt="<?php echo $mgr->getOrderData('msg', 'belong_chg_name') ?>">所属</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('post_name');"><img src="<?php echo $mgr->getOrderData('src', 'post_name') ?>" alt="<?php echo $mgr->getOrderData('msg', 'post_name') ?>">役職</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('kanji_name');"><img src="<?php echo $mgr->getOrderData('src', 'kanji_name') ?>" alt="<?php echo $mgr->getOrderData('msg', 'kanji_name') ?>">氏名</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('kana_name');"><img src="<?php echo $mgr->getOrderData('src', 'kana_name') ?>" alt="<?php echo $mgr->getOrderData('msg', 'kana_name') ?>">カナ氏名</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('end_date');"><img src="<?php echo $mgr->getOrderData('src', 'end_date') ?>" alt="<?php echo $mgr->getOrderData('msg', 'end_date') ?>">利用期限</a></th>
            <th width="60" scope="col">詳細</th>
          </tr>
<?php     foreach ($mgr->aryList AS $id => $data) { ?>
          <tr>
            <td><?php echo $data['login_id'] ?></td>
            <td align="center"><?php echo $data['staffcode'] ?></td>
			<td><?php echo $data['belong_sec_name']."<br />".$data['belong_chg_name'] ?></td>
            <td><?php echo $data['post_name'] ?></td>
            <td><?php echo $data['kanji_name'] ?></td>
            <td><?php echo $data['kana_name'] ?></td>
            <td align="center"><?php echo $data['end_date'] ?></td>
            <td align="center">[<a href="javascript:usersEdit(<?php echo $id ?>);">詳細</a>]</td>
          </tr>
<?php     } ?>
        </table>
        <table cellspacing="0" class="listTabB">
          <tr>
            <td><?php echo $mgr->getListNavi() ?></td>
          </tr>
        </table>
<?php   } else { ?>
        <table cellspacing="0">
          <tr>
            <td align="left">条件に一致するデータはありませんでした。</td>
          </tr>
        </table>
<?php   } ?>
      </div>
<?php } ?>
<?php include("view/foot.tpl") ?>

