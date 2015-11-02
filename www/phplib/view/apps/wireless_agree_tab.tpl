              <table border="0" cellpadding="2" cellspacing="0" class="listTab">
                <tr>
                  <th scope="col">VLAN</th>
                </tr>
<?php if (is_array($mgr->output['vlan_area_name'])) { ?>
<?php   foreach ($mgr->output['vlan_area_name'] AS $vlan_id => $dummy) { ?>
<?php     if ($mgr->output['is_vlan_admin'] || $mgr->isAdminUser()) { ?>
                <tr>
                  <td><?php echo $mgr->getOutputData('vlan_area_name', $vlan_id) ?>
<?php       if ($mgr->getOutputData('agree_flg', $vlan_id) == '0') { ?>
                    　<span style="color:#f00;">承認待ち</span>
<?php       }else if ($mgr->getOutputData('busy_flg', $vlan_id) == '1') { ?>
                    　<span style="color:#00f;">使用中</span>
<?php       } ?>
                  </td>
                </tr>
<?php     } ?>
<?php   } ?>
<?php } ?>
              </table>