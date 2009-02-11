<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 *
 */

$common_func = new CommonFunctions();
$payPeriodList = $this->popArr['payPeriodList'];
?>
<script type="text/javaScript"><!--//--><![CDATA[//><!--
function decimalCurr(txt) {
	regExp = /^[0-9]+(\.[0-9]+)*$/;

	return regExp.test(txt.value);
}


function addEXTPayment() {

	if(document.frmEmp.cmbCurrCode.value=='0') {
			alert("<?php echo $lang_Error_FieldShouldBeSelected; ?>!");
			document.frmEmp.cmbCurrCode.focus();
			return;
	}

	var cnt=document.frmEmp.txtBasSal;
	if(!decimalCurr(cnt)) {
		alert("<?php echo $lang_Error_FieldShouldBeNumeric; ?>");
		cnt.focus();
		return;
	}

	var min = eval(document.frmEmp.txtMinCurrency.value);
	var max = eval(document.frmEmp.txtMaxCurrency.value);

	if(min > cnt.value || max < cnt.value) {
		alert("<?php echo $lang_hremp_SalaryShouldBeWithinMinAndMa; ?>");
		cnt.focus();
		return;
	}

document.frmEmp.paymentSTAT.value="ADD";
qCombo(14);
}

function editEXTPayment() {

	var cnt=document.getElementById('etxtBasSal');
	if(!decimalCurr(cnt)) {
		alert("<?php echo $lang_Error_FieldShouldBeNumeric; ?>");
		cnt.focus();
		return;
	}

	var min = eval(document.frmEmp.txtMinCurrency.value);
	var max = eval(document.frmEmp.txtMaxCurrency.value);

	if(min > cnt.value || max < cnt.value) {
		alert("<?php echo $lang_hremp_SalaryShouldBeWithinMinAndMa; ?>");
		cnt.focus();
		return;
	}

  document.frmEmp.paymentSTAT.value="EDIT";
  qCombo(14);
}

function delEXTPayment() {

	var check = false;
	with (document.frmEmp) {
		for (var i=0; i < elements.length; i++) {
			if ((elements[i].name == 'chkpaydel[]') && (elements[i].checked == true)) {
				check = true;
			}
		}
	}

	if(!check) {
		alert('<?php echo $lang_Error_SelectAtLeastOneRecordToDelete; ?>')
		return;
	}

    document.frmEmp.paymentSTAT.value="DEL";
	qCombo(14);
}

function viewPayment(pay,curr) {

	document.frmEmp.action = document.frmEmp.action + "&PAY=" + pay + "&CUR=" + curr;
	document.frmEmp.pane.value = 14;
	document.frmEmp.submit();
}
//--><!]]></script>
<?php
$supervisorEMPMode = false;
if ((isset($_SESSION['isSupervisor']) && $_SESSION['isSupervisor']) && (isset($_GET['reqcode']) && ($_GET['reqcode'] === "EMP")) ) {
	$supervisorEMPMode = true;
}
if(isset($this->getArr['capturemode']) && $this->getArr['capturemode'] == 'updatemode') { ?>
<div id="parentPanePayments" >
        <input type="hidden" name="paymentSTAT" value=""/>
<?php
	$salGrd = $this->popArr['salGrd'];
//	if($salGrd === null) {
//		$pleaseSelectJobTitle = preg_replace('/\{(.*)\}/', "<a href='javascript:displayLayer(2)'>$1</a>", $lang_hremp_PleaseSelectJobTitle);
//		echo "<p align='center'><strong>$lang_hremp_AddPayGrade</strong></p>";
//	}
?>
<?php
	if(isset($this->popArr['editPaymentArr'])) {
	 	$edit = $this->popArr['editPaymentArr'];
?>
	<div id="editPanePayments">
 			<table width="100%" border="0" cellpadding="5" cellspacing="0" class="">
        		<tr>
                      <td><?php echo $lang_hrEmpMain_paygrade?></td>
    				  <td><strong>
    				  <?php $salgradelist = $this->popArr['salgradelist'];
    				  if(!isset($salgradelist) || !is_array($salgradelist)) {
    				  	echo $lang_Common_NotApplicable;
    				  } else {
    				  	if(isset($salGrd)) {
    				  		?><input type='hidden' name='cmbSalaryGrade' id='cmbSalaryGrade' value='<?php echo $this->popArr['salGrd'];?>'/>
    				  		<?php
    				  		for($c=0; $salgradelist && count($salgradelist) > $c; $c++) {
    				    		if($this->popArr['salGrd'] == $salgradelist[$c][0]) {
    				    			 echo $salgradelist[$c][1];
    				    		}
    				  		}
    				  	}
    				  }
    				 ?>


    				  </strong></td>
					</tr>
					  <tr>
						<td valign="top"><?php echo $lang_hrEmpMain_currency?></td>
						<td align="left" valign="top"><input type="hidden" name="cmbCurrCode" value="<?php echo $edit[0][2]?>" />
						<strong>
<?php
						$currlist=$this -> popArr['currlist'];
						for($c=0;count($currlist)>$c;$c++)
						    if($currlist[$c][2]==$edit[0][2])
						       echo $currlist[$c][0];
?>
						</strong></td>
					  </tr>
					  <tr>
						<td valign="top"><?php echo $lang_hrEmpMain_minpoint?></td>
						<td align="left" valign="top"><strong>
<?php
						for($c=0;count($currlist)>$c;$c++)
						    if($currlist[$c][2]==$edit[0][2]) {
						    	echo "<input type='hidden' name='txtMinCurrency' value='" .$currlist[$c][3]. "'/>";
						    	echo $common_func->formatSciNo($currlist[$c][3]);
						    }
?>
						</strong></td>
					  </tr>
					  <tr>
						<td valign="top"><?php echo $lang_hrEmpMain_maxpoint?></td>
						<td align="left" valign="top"><strong>
<?php
						for($c=0;count($currlist)>$c;$c++)
						    if($currlist[$c][2]==$edit[0][2]) {
						    	echo "<input type='hidden' name='txtMaxCurrency' value='" .$currlist[$c][5]. "'/>";
						    	echo $common_func->formatSciNo($currlist[$c][5]);
						    }
?>
						</strong></td>
					  </tr>
					  <tr>
						<td valign="top"><?php echo $lang_hrEmpMain_bassalary?></td>
						<td align="left" valign="top"><input type="text" name="txtBasSal" id="etxtBasSal" value="<?php echo $common_func->formatSciNo($edit[0][3]);?>"/>
						</td>
					  </tr>
					  <tr>
						<td valign="top"><?php echo $lang_hrEmpMain_payfrequency; ?></td>
						<td align="left" valign="top">
							<select name="cmbPayPeriod" id="cmbPayPeriod">
					              	<option selected="selected" value="0">-- <?php echo $lang_Common_Select?> --</option>
							<?php
								foreach ($payPeriodList as $period) {
									$selected = ($period->getCode() == $edit[0][4])? "selected" : "";
							    	echo "<option " . $selected . " value='" . $period->getCode() . "'>" . $period->getName() . "</option>";
								}
							?>
							</select>
						</td>
					  </tr>
                  </table>
<?php			if(!$supervisorEMPMode && $locRights['edit']) { ?>
<div class="formbuttons">
    <input type="button" class="savebutton" name="btnEditPayment" id="btnEditPayment"
    	value="<?php echo $lang_Common_Save;?>"
    	title="<?php echo $lang_Common_Save;?>"
    	onmouseover="moverButton(this);" onmouseout="moutButton(this);"
    	onclick="editEXTPayment(); return false;"/>
</div>
<?php			}  ?>
       </div>
<?php } else { ?>
	<div id="addPanePayments" class="<?php echo ($this->popArr['rsetPayment'] != null)?"addPane":""; ?>" >
				<table width="100%" border="0" cellpadding="5" cellspacing="0" class="">
                    <tr>
                      <td><?php echo $lang_hrEmpMain_paygrade; ?></td>
    				  <td><strong>
    				 <?php $salgradelist = $this->popArr['salgradelist'];
    				  if(!isset($salgradelist) || !is_array($salgradelist)) {
    				  	echo $lang_Common_NotApplicable;
    				  } else {
    				  	if(isset($salGrd)) {
    				  		?><input type='hidden' name='cmbSalaryGrade' id='cmbSalaryGrade' value='<?php echo $this->popArr['salGrd'];?>'/>
    				  		<?php
    				  		for($c=0; $salgradelist && count($salgradelist) > $c; $c++) {
    				    		if($this->popArr['salGrd'] == $salgradelist[$c][0]) {
    				    			 echo $salgradelist[$c][1];
    				    		}
    				  		}
    				  	}else {
    				  		?>
                            <script type="text/javascript">
                               function reCurr(){
                                    if($('cmbCurrCode').value == '0'){
                                        setTimeout("reCurr()", 2000);
                                    } else {
                                        setTimeout("xajax_getMinMaxCurrency($('cmbCurrCode').value, document.frmEmp.cmbSalaryGrade.value)", 1000);
                                    }
                               }
                               function setCurrCode() {
                                    xajax_getUnAssignedCurrencyList($('cmbSalaryGrade').value);
                                    reCurr();
                               }
                            </script>
    				  		<select <?php echo (!$supervisorEMPMode && $locRights['add'])? '':'disabled="disabled"'?> onchange="setCurrCode();" id='cmbSalaryGrade' name='cmbSalaryGrade'>
    				  		<option value="0">-- <?php echo $lang_hremp_SelectPayGrade; ?> --</option>
    				  		<?php
    				  		for($c=0; $salgradelist && count($salgradelist) > $c; $c++) {
    				    		?><option value="<?php echo $salgradelist[$c][0]; ?>"> <?php echo $salgradelist[$c][1]; ?> </option>
    				    		<?php
    				  		}
    				  		?></select>
    				  		<?php
    				  	}

    				  }
    				 ?>
    				  </strong></td>
					</tr>
					  <tr>
						<td valign="top"><?php echo $lang_hrEmpMain_currency; ?></td>
						<td align="left" valign="top"><select <?php echo (!$supervisorEMPMode && $locRights['add'])? '':'disabled="disabled"'?> onclick="xajax_getMinMaxCurrency(this.value, document.frmEmp.cmbSalaryGrade.value); " id='cmbCurrCode' name='cmbCurrCode'>
                       						<option value="0">-- <?php echo $lang_hremp_SelectCurrency; ?> --</option>
<?php
						$curlist= $this->popArr['unAssCurrList'];
						for($c=0;$curlist && count($curlist)>$c;$c++)
								   echo "<option value=" . $curlist[$c][2] . ">" . $curlist[$c][0] . "</option>";
?>
							</select></td>
					  </tr>
					  <tr>
						<td valign="top"><?php echo $lang_hrEmpMain_minpoint?></td>
						<td align="left" valign="top">
							<input type='hidden' name='txtMinCurrency' id='txtMinCurrency' />
							<div id='divMinCurrency'><strong>-<?php echo $lang_Common_NotApplicable;?>-</strong></div>
						</td>
					  </tr>
					  <tr>
						<td valign="top"><?php echo $lang_hrEmpMain_maxpoint?></td>
						<td align="left" valign="top">
							<input type='hidden' name='txtMaxCurrency' id='txtMaxCurrency' />
							<div id='divMaxCurrency'><strong>-<?php echo $lang_Common_NotApplicable;?>-</strong></div>
						</td>
					  </tr>
					  <tr>
						<td valign="top"><?php echo $lang_hrEmpMain_bassalary?></td>
						<td align="left" valign="top"><input type="text" <?php echo (!$supervisorEMPMode && $locRights['add']) ? '':'disabled="disabled"'?> name="txtBasSal"/>
						</td>
					  </tr>
					  <tr>
						<td valign="top"><?php echo $lang_hrEmpMain_payfrequency;?></td>
						<td align="left" valign="top">
						<select name="cmbPayPeriod" id="cmbPayPeriod"
							<?php echo (!$supervisorEMPMode && $locRights['add'] ) ? '':'disabled="disabled"'?> >
					              	<option selected="selected" value="0">-- <?php echo $lang_Common_Select?> --</option>
							<?php
								foreach ($payPeriodList as $period) {
							    	echo "<option value='" . $period->getCode() . "'>" . $period->getName() . "</option>";
								}
							?>
							</select>
						</td>
					  </tr>
                   </table>
<?php	if(!$supervisorEMPMode && $locRights['add']) { ?>
<div class="formbuttons">
    <input type="button" class="savebutton" name="btnAddPayment" id="btnAddPayment"
    	value="<?php echo $lang_Common_Save;?>"
    	title="<?php echo $lang_Common_Save;?>"
    	onmouseover="moverButton(this);" onmouseout="moutButton(this);"
    	onclick="addEXTPayment(); return false;"/>
</div>
<?php	} ?>
	</div>
<?php } ?>
<?php
$rset = $this->popArr['rsetPayment'];
$currlist=$this->popArr['currAlllist'];

//Handling the table View
if (($rset != null) && ($currlist != null)) { ?>
	<div class="subHeading"><h3><?php echo $lang_hrEmpMain_assignedsalary; ?></h3></div>

	<div class="actionbar">
		<div class="actionbuttons">
<?php if($locRights['add']) { ?>
					<input type="button" class="addbutton"
						onclick="showAddPane('Payments');" onmouseover="moverButton(this);" onmouseout="moutButton(this);"
						value="<?php echo $lang_Common_Add;?>" title="<?php echo $lang_Common_Add;?>"/>
<?php } ?>
<?php	if(!$supervisorEMPMode && $locRights['delete']) { ?>
					<input type="button" class="delbutton"
						onclick="delEXTPayment();" onmouseover="moverButton(this);" onmouseout="moutButton(this);"
						value="<?php echo $lang_Common_Delete;?>" title="<?php echo $lang_Common_Delete;?>"/>

<?php 	} ?>
			</div>
		</div>


	<table width="100%" cellspacing="0" cellpadding="0" class="data-table">
		<thead>
			<tr>
              	 <td></td>
				 <td><strong><?php echo $lang_hrEmpMain_currency?></strong></td>
				 <td><strong><?php echo $lang_hrEmpMain_bassalary?></strong></td>
				 <td><strong><?php echo $lang_hrEmpMain_payfrequency?></strong></td>
			</tr>
		</thead>
		<tbody>
<?php

    for($c=0; $rset && $c < count($rset); $c++) {
		$cssClass = ($c%2) ? 'even' : 'odd';
        echo '<tr class="' . $cssClass . '">';
            echo "<td><input type='checkbox' class='checkbox' name='chkpaydel[]' value='" . $rset[$c][1] ."|" . $rset[$c][2] . "'/></td>";
			for($a=0;count($currlist)>$a;$a++)
			    if($currlist[$a][0]==$rset[$c][2])
				   $fname=$currlist[$a][1];
            ?><td><a href="javascript:viewPayment('<?php echo $rset[$c][1]?>','<?php echo $rset[$c][2]?>')"><?php echo $fname?></a></td><?php
            echo '<td>' . $common_func->formatSciNo($rset[$c][3]) .'</td>';

            $payFrequency = "--";

            if (isset($rset[$c][4])) {
            	$payPeriodCode = $rset[$c][4];
            	if (array_key_exists($payPeriodCode, $payPeriodList)) {
	            	$payFrequency = $payPeriodList[$payPeriodCode]->getName();
            	}
            }
            echo '<td>' . $payFrequency .'</td>';
        echo '</tr>';
        }

?>
	</tbody>
</table>
<?php } ?>
<?php } ?>
</div>
