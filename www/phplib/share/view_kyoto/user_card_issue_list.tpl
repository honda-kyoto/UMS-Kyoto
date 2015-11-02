<?php include("view/head.tpl") ?>
      <input type="hidden" name="user_id" id="user_id" value="">
      <input type="hidden" name="page" id="page" value="1">
      <input type="hidden" name="order" id="order" value="">
      <h3>■検索条件</h3>
      <table border="0" cellpadding="2" cellspacing="0" class="searchTab">
        <tr>
          <th width="110">登録日・更新日</th>
          <td><table border="0" cellspacing="2" cellpadding="0">
                <tr>
                  <td><input name="issue_from" type="text" id="issue_from" onchange="cal_ifd.getFormValue(); cal_ifd.hide();" onclick="cal_ifd.write(); cal_itd.hide();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('issue_from') ?>" /><br /><div id="caldiv_ifd"></div></td>
                  <td>～</td>
                  <td><input name="issue_to" type="text" id="issue_to" onchange="cal_itd.getFormValue(); cal_itd.hide();" onclick="cal_itd.write(); cal_ifd.hide();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('issue_to') ?>" /><br /><div id="caldiv_itd"></div></td>
                </tr>
              </table>
           </td>
          <th width="110">オプション</th>
          <td><table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td><input type="radio" name="search_option" id="search_option_0" value="0" <?php echo $mgr->getCheckData('search_option', '0') ?> /></td>
              <td><label for="search_option_0">全て</label></td>
              <td>&nbsp;&nbsp;&nbsp;</td>
              <td><input type="radio" name="search_option" id="search_option_1" value="1" <?php echo $mgr->getCheckData('search_option', '1') ?>  /></td>
              <td><label for="search_option_1">新規登録のみ</label></td>
              <td>&nbsp;&nbsp;&nbsp;</td>
              <td><input type="radio" name="search_option" id="search_option_2" value="2" <?php echo $mgr->getCheckData('search_option', '2') ?>  /></td>
              <td><label for="search_option_2">更新のみ</label></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <th>キー番号</th>
          <td><input type="text" name="key_number" id="key_number" value="<?php echo $mgr->getRequestData('key_number') ?>" size="16" maxlength="16"></td>
          <th>メイン・サブ</th>
          <td><table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td><input type="radio" name="data_type" id="data_type_1" value="1" <?php echo $mgr->getCheckData('data_type', '1') ?>  /></td>
              <td><label for="data_type_1">メインデータのみ</label></td>
              <td>&nbsp;&nbsp;&nbsp;</td>
              <td><input type="radio" name="data_type" id="data_type_0" value="0" <?php echo $mgr->getCheckData('data_type', '0') ?> /></td>
              <td><label for="data_type_0">サブを含む全て</label></td>
            </tr>
          </table></td>
        </tr>
      </table>
      <div class="searchBlock">
        <a href="javascript:search();"  /><img src="image/btn_search.gif" title="検索" /></a>
        <a href="javascript:clearCond();" /><img src="image/btn_clear.gif" title="条件クリア" /></a>
      </div>
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
            <th scope="col"><input type="checkbox" name="allcheck" value="1" onClick="allCheck(this, 'checked_id')" /></th>
<!--
            <th scope="col"><a class="sortlink" href="javascript:sort('data_type');"><img src="<?php echo $mgr->getOrderData('src', 'data_type') ?>" alt="<?php echo $mgr->getOrderData('msg', 'data_type') ?>">種別</a></th>
-->
            <th scope="col"><a class="sortlink" href="javascript:sort('key_number');"><img src="<?php echo $mgr->getOrderData('src', 'key_number') ?>" alt="<?php echo $mgr->getOrderData('msg', 'key_number') ?>">キー番号</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('uid');"><img src="<?php echo $mgr->getOrderData('src', 'uid') ?>" alt="<?php echo $mgr->getOrderData('msg', 'uid') ?>">UID</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('kanji_name');"><img src="<?php echo $mgr->getOrderData('src', 'kanji_name') ?>" alt="<?php echo $mgr->getOrderData('msg', 'kanji_name') ?>">氏名</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('belong_name');"><img src="<?php echo $mgr->getOrderData('src', 'belong_name') ?>" alt="<?php echo $mgr->getOrderData('msg', 'belong_name') ?>">所属</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('post_name');"><img src="<?php echo $mgr->getOrderData('src', 'post_name') ?>" alt="<?php echo $mgr->getOrderData('msg', 'post_name') ?>">役職</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('update_time');"><img src="<?php echo $mgr->getOrderData('src', 'update_time') ?>" alt="<?php echo $mgr->getOrderData('msg', 'update_time') ?>">登録・更新日時</a></th>
            <th scope="col">登録</th>
            <th scope="col">再</th>
            <th scope="col">停</th>
            <th scope="col">削</th>
            </tr>
<?php     foreach ($mgr->aryList AS $id => $data) { ?>
          <tr>
            <td align="center"><input type="checkbox" name="checked_id[]" name="checked_id_<?php echo $id ?>" value="<?php echo $id ?>" /></td>
<!--
            <td><?php echo $data['data_type'] ?></td>
-->
            <td><?php echo $data['key_number'] ?></td>
            <td><?php echo $data['uid'] ?></td>
            <td><?php echo $data['kanji_name'] ?></td>
            <td><?php echo $data['belong_name'] ?></td>
            <td><?php echo $data['post_name'] ?></td>
            <td><?php echo $data['issue_time'] ?></td>
            <td align="center"><?php echo $data['data_type_name'] ?></td>
            <td align="center"><span style="font-size: 1.2em;"><?php echo $data['reissue'] ?></span></td>
            <td align="center"><span style="font-size: 1.2em;"><?php echo $data['suspend'] ?></span></td>
            <td align="center"><span style="font-size: 1.2em;"><?php echo $data['delete'] ?></span></td>
            </tr>
<?php     } ?>
        </table>
        <table cellspacing="0" class="listTabB">
          <tr>
            <td><?php echo $mgr->getListNavi() ?></td>
          </tr>
          <tr>
            <td align="right"><input type="button" name="btnOutput" value="　　　チェックしたデータをまとめて出力　　　" OnClick="javascript:outputCardData();"></td>
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