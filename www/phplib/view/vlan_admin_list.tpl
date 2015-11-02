<?php include("view/head.tpl") ?>
      <input type="hidden" name="vlan_room_id" id="vlan_room_id" value="<?php echo $mgr->getRequestData('vlan_room_id') ?>">
      <input type="hidden" name="vlan_id" id="vlan_id" value="<?php echo $mgr->getRequestData('vlan_id') ?>">
      <input type="hidden" name="list_no" id="list_no" value="">
      <input type="hidden" name="user_id" id="user_id" value="">
        <table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td align="right"><div id="editAll1"><input type="button" value="　　　マスタメンテナンスに戻る　　　" onClick="location.href='msts.php'" /></div></td>
          </tr>
        </table>
        <h3>■『<?php echo $mgr->getOutputData('vlan_name') ?>』の管理者一覧</h3>
        <table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td align="right"><input type="button" value="　　　管理者を追加　　　" onclick="searchAdminUser();"></div></td>
          </tr>
        </table>
        <table width="100%" border="0" cellpadding="2" cellspacing="0" class="listTab" id="listTab">
          <tr>
            <th scope="col">VLAN管理者</th>
            <th scope="col">削除</th>
          </tr>
<?php if (is_array($mgr->output['admin_name']) && count($mgr->output['admin_name']) > 0) { ?>
<?php   foreach ($mgr->output['admin_name'] AS $no => $data) { ?>
          <tr>
            <td><?php echo $mgr->getOutputData('admin_name', $no) ?>(<?php echo $mgr->getOutputData('belong_name', $no) ?>)</td>
            <td width="50" align="center">[<a href="javascript:;" onclick="deleteVlanAdmin(<?php echo $no ?>);">削除</a>]</td>
          </tr>
<?php     } ?>
<?php   } ?>
        </table>
        <table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
          <tr>
            <td>
              <input type="button" value="　　VLAN一覧に戻る　　" onclick="returnVlan();" />
            </td>
          </tr>
        </table>
<?php include("view/foot.tpl") ?>
