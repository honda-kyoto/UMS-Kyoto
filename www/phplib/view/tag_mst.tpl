<?php include("view/head.tpl") ?>
      <input type="hidden" name="post_id" id="post_id" value="">

        <table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td align="right"><div id="editAll1"><input type="button" value="　　　入退室カード管理に戻る　　　" onClick="location.href='inout_room.php'" /></div></td>
          </tr>
        </table>

        <h3>■未発行タグ一覧</h3>
<!--
        <table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td align="right"><div id="editAll1"><input type="button" value="　　　全て更新　　　" onclick="mstEditAll();"></div></td>
          </tr>
        </table>
-->
        <table width="100%" border="0" cellpadding="2" cellspacing="0" class="listTab" id="listTab">
          <tr class="nodrop nodrag">
            <th scope="col" width="60">表示順</th>
            <th scope="col">タグ番号</th>
            <th scope="col">uid</th>
            <th scope="col">更新</th>
            <th scope="col">削除</th>
          </tr>
          <tr class="nodrop nodrag">
            <td align="center">（新規）</td>
            <td><input name="new_post_name" type="text" id="new_post_name" size="30" maxlength="16" value="<?php echo $mgr->getRequestData('new_post_name') ?>" /></td>
            <td><input name="new_tag_uid" type="text" id="new_tag_uid" size="30" maxlength="16" value="<?php echo $mgr->getRequestData('new_tag_uid') ?>" /></td>
            <td align="center">-</td>
            <td align="center">[<a href="javascript:;" onclick="insertPost();">追加</a>]</td>
          </tr>
<?php if (is_array($mgr->request['post_name']) && count($mgr->request['post_name']) > 0) { ?>
          <tbody class="listTab_TB" id="listTab_TB">
<?php   foreach ($mgr->request['post_name'] AS $id => $data) { ?>
          <tr id="<?php echo $id; ?>">
            <td align="center" class="SortTypeCell">&nbsp;</td>
            <td><input name="post_name[<?php echo $id ?>]" type="text" id="post_name_<?php echo $id ?>" value="<?php echo $mgr->getRequestData('post_name', $id) ?>" size="30" maxlength="16" /></td>
            <td><input name="tag_uid[<?php echo $id ?>]" type="text" id="tag_uid_<?php echo $id ?>" value="<?php echo $mgr->getRequestData('tag_uid', $id) ?>" size="30" maxlength="16" /></td>
            <td width="50" align="center">[<a href="javascript:;" onclick="updatePost(<?php echo $id ?>);">更新</a>]</td>
            <td width="50" align="center">[<a href="javascript:;" onclick="deletePost(<?php echo $id ?>);">削除</a>]</td>
          </tr>
<?php     } ?>
          </tbody>
<?php   } ?>
        </table>
<!--
        <table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td align="right"><div id="editAll2"><input type="button" value="　　　全て更新　　　" onclick="mstEditAll();"></div></td>
          </tr>
        </table>
-->
        <table border="0" cellpadding="2" cellspacing="0" class="AjaxTableDisable" id="nowLoad">
          <tr>
            <td><img src="./image/loading.gif" alt="並び替え中です・・・" /></td>
          </tr>
          <tr>
            <td>並び替え中です・・・</td>
          </tr>
        </table>
<?php include("view/foot.tpl") ?>

