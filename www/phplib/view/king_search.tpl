<?php include("view/head.tpl") ?>
      <input type="hidden" name="mlist_id" id="mlist_id" value="">
      <input type="hidden" name="entry_no" id="entry_no" value="">
      <input type="hidden" name="page" id="page" value="<?php echo $mgr->getRequestData('page') ?>">
      <input type="hidden" name="order" id="order" value="">
      <div style="clear:both;"></div>
      <h3>■検索条件</h3>
      <table border="0" cellpadding="2" cellspacing="0" class="searchTab">
        <tr>
          <th width="80">氏名</th>
          <td width="200"><input name="king_name" type="text" id="king_name" size="30" value="<?php echo $mgr->getRequestData('king_name') ?>"  /></td>
          <th width="80">カナ</th>
          <td width="200"><input name="king_name_kana" type="text" id="king_name_kana" size="30" value="<?php echo $mgr->getRequestData('king_name_kana') ?>" /></td>
          <td class="emptycol">&nbsp;</td>
        </tr>
      </table>
      <div class="searchBlock">
        <a href="javascript:search();"  /><img src="image/btn_search.gif" title="検索" /></a>
        <a href="javascript:clearCond();" /><img src="image/btn_clear.gif" title="条件クリア" /></a>
      </div>
<?php echo $test; ?>
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
            <th scope="col" width="60"><a class="sortlink" href="javascript:sort('king_id');">KING ID</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('king_name');">氏名</a></th>
            <th scope="col"><a class="sortlink" href="javascript:sort('king_name_kana');">カナ</a></th>
          </tr>
<?php     foreach ($mgr->aryList AS $id => $data) { ?>
          <tr>
            <td align="right"><?php echo $id ?></td>
            <td><?php echo $data['king_name'] ?></td>
            <td><?php echo $data['king_name_kana'] ?></td>
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

