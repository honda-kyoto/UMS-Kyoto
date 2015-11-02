<?php include("view/win_head.tpl") ?>
      <input type="hidden" name="user_id" id="user_id" value="">
      <input type="hidden" name="page" id="page" value="<?php echo $mgr->getRequestData('page') ?>">
<?php include("view/users/search_cond_tab.tpl") ?>
<?php if (is_array(@$mgr->aryList)) { ?>
      <div class="resultBlock">
        <h3>■検索結果一覧</h3>
<?php   if (count($mgr->aryList) > 0) { ?>
        <table cellspacing="0" class="listTabH">
          <tr>
            <td><?php echo $mgr->getListNavi() ?></td>
            <td align="right">表示件数：
                <select name="list_max" onChange="listMax();">
                <?php echo $mgr->getSelectList('list_max') ?>
              </select></td>
          </tr>
        </table>
        <table width="100%" border="0" cellpadding="2" cellspacing="0" class="listTab">
          <tr>
            <th scope="col" width="56">設定</th>
            <th scope="col" width="56">統合ID</th>
            <th scope="col" width="62">カードNo.</th>
            <th scope="col">所属</th>
            <th scope="col">職種</th>
            <th scope="col">役職</th>
            <th scope="col">氏名</th>
            <th scope="col">カナ氏名</th>
            <th scope="col">利用期限</th>
          </tr>
<?php     foreach ($mgr->aryList AS $id => $data) { ?>
          <tr>
            <td align="center"><input type="button" value="　設　定　" onclick="setAppUserId(<?php echo $id ?>, '<?php echo $data['kanji_name'] ?>');"></td>
            <td align="center"><?php echo $data['login_id'] ?></td>
            <td align="center"><?php echo $data['staffcode'] ?></td>
            <td><?php echo $data['belong_name'] ?></td>
            <td><?php echo $data['job_name'] ?></td>
            <td><?php echo $data['post_name'] ?></td>
            <td><?php echo $data['kanji_name'] ?></td>
            <td><?php echo $data['kana_name'] ?></td>
            <td align="center"><?php echo $data['end_date'] ?></td>
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

