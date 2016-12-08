<?php
class AutomatedClassSupplier{
    public static function AutomatedTransaction($id,$mode){
        $purchasesInv=PurchasesInvoices::model()->findByPk($id);
        $suppliersCredit=SuppliersCredit::model()->find(array('condition'=>'credited_from=:invoice','params'=>array(':invoice'=>$id)));
        $remainingCredit=$purchasesInv->credited-$purchasesInv->balance;
        if($remainingCredit>0){
            $purchasesInv->credited=$purchasesInv->balance;
            $modelsCreditedFrom=AutomatedTransactionSuppliers::model()->findAll(array(
                'condition'=>'to_purchases_invoice_id=:invoice_id','params'=>array(':invoice_id'=>$purchasesInv->id)));
            $creditedModels=array();
            foreach($modelsCreditedFrom as $indCredited)
            {
                $i=0;
                $creditAssigned=$indCredited->amount;
                $creditedModel=SuppliersCredit::model()->find(array('condition'=>'credited_from=:invoice','params'=>array(':invoice'=>$indCredited->from_purchases_invoice_id)));
                if(isset($creditedModel)){
                    if($remainingCredit>=$creditAssigned)
                    {
                        $indCredited->delete();
                        $creditedModel->credit_assigned-=$creditAssigned;
                        $creditedModel->save();
                        $remainingCredit-=$creditAssigned;
                        $creditedModels[$i]=$creditedModel;
                        $i++;
                    }
                    else{
                        $indCredited->amount-=$remainingCredit;
                        $creditedModel->credit_assigned-=$remainingCredit;
                        $creditedModel->save();
                        $indCredited->save();
                        $creditedModels[$i]=$creditedModel;
                        break;
                    }
                }

            }
            $purchasesInv->save();
            foreach($creditedModels as $indvCredited)
            {
                AutomatedClassSupplier::TransferringCredit($indvCredited);
            }
        }
        else{
            if($remainingCredit<0){
                $remainingCredit=-1*$remainingCredit;
                AutomatedClassSupplier::GetCreditfor($purchasesInv,$remainingCredit);
            }

        }
        if(isset($suppliersCredit)){
            $creditRemainingOfThisModel=$suppliersCredit->amount-$suppliersCredit->credit_assigned;
            $creditTakerInvoices=array();
            if($creditRemainingOfThisModel>0){
                AutomatedClassSupplier::TransferringCredit($suppliersCredit);
            }
            else
            {
                if($creditRemainingOfThisModel<0){
                    $overAssignedCredit=-1*$creditRemainingOfThisModel;
                    $creditTakers=AutomatedTransactionSuppliers::model()->findAll(array('condition'=>'from_purchases_invoice_id=:invoice','params'=>array(':invoice'=>$suppliersCredit->credited_from)));

                    $i=0;
                    foreach($creditTakers as $creditTaker){
                        if($overAssignedCredit>0){
                            $creditTakerInvoice=PurchasesInvoices::model()->findByPk($creditTaker->to_purchases_invoice_id);
                            if($creditTaker->amount<=$overAssignedCredit){
                                $creditDeducted=$creditTaker->amount;
                                $creditTaker->delete();
                            }
                            else{
                                $creditDeducted=$overAssignedCredit;
                                $creditTaker->amount-=$creditDeducted;
                                $creditTaker->save();
                            }
                            $creditTakerInvoice->credited-=$creditDeducted;
                            $creditTakerInvoice->save();
                            $overAssignedCredit-=$creditDeducted;
                            $creditTakerInvoices[$i]=$creditTakerInvoice;
                            $i++;
                        }
                        else{

                        }
                    }

                }
                if($suppliersCredit->amount==0)
                    $suppliersCredit->delete();
                else{
                    $suppliersCredit->credit_assigned=$suppliersCredit->amount;
                    $suppliersCredit->save();
                }
                foreach($creditTakerInvoices as $creditTakerInv){
                    $creditToPay=$creditTakerInv->balance-$creditTakerInv->credited;
                    AutomatedClassSupplier::GetCreditfor($creditTakerInv,$creditToPay);
                }
            }
        }
    }
    public static function TransferringCredit($indvCredited)
    {
        $creditRemainingOfThisModel=$indvCredited->amount-$indvCredited->credit_assigned;
        foreach(PurchasesInvoices::model()->findAll(array('condition'=>'supplier_id=:supplier','params'=>array(':supplier'=>$indvCredited->supplier_id))) as $indvPurchasesInvoice){
            if($creditRemainingOfThisModel>0){
                //echo $creditRemainingOfThisModel;
                $amountToGetBalanced=$indvPurchasesInvoice->balance-$indvPurchasesInvoice->credited;
                //echo $amountToGetBalanced;

                if($amountToGetBalanced>0){
                    if($creditRemainingOfThisModel>$amountToGetBalanced){
                        $amountCredited=$amountToGetBalanced;
                    }
                    else{
                        $amountCredited=$creditRemainingOfThisModel;
                    }
                    $indvPurchasesInvoice->credited+=$amountCredited;
                    $indvCredited->credit_assigned+=$amountCredited;
                    $creditRemainingOfThisModel-=$amountCredited;
                    $transaction=new AutomatedTransactionSuppliers;
                    $transaction->paid_date=$indvCredited->credited_date;
                    $transaction->supplier_id=$indvCredited->supplier_id;
                    $transaction->from_purchases_invoice_id=$indvCredited->credited_from;
                    $transaction->to_purchases_invoice_id=$indvPurchasesInvoice->id;
                    $transaction->amount=$amountCredited;
                    $indvPurchasesInvoice->save();
                    $indvCredited->save();
                    $transaction->save();
                }
            }
            else{

            }

        }
    }

    public static function GetCreditfor($purchasesInvoice,$remainingCredit)
    {
        foreach(SuppliersCredit::model()->findAll(array('condition'=>'supplier_id=:supplier','params'=>array(':supplier'=>$purchasesInvoice->supplier_id))) as $indModel){
            $creditRemained=$indModel->amount-$indModel->credit_assigned;
            if($remainingCredit<=0){
                break;
            }

            else{
                if($creditRemained>$remainingCredit)
                    $creditBorrowed=$remainingCredit;
                else
                    $creditBorrowed=$creditRemained;
                $transaction=new AutomatedTransactionSuppliers;
                $transaction->paid_date=$indModel->credited_date;
                $transaction->from_purchases_invoice_id=$indModel->credited_from;
                $transaction->to_purchases_invoice_id=$purchasesInvoice->id;
                $transaction->amount=$creditBorrowed;
                $transaction->supplier_id=$purchasesInvoice->supplier_id;
                $indModel->credit_assigned+=$creditBorrowed;
                $purchasesInvoice->credited+=$creditBorrowed;
                $purchasesInvoice->save();
                $transaction->save();
                $indModel->save();
                $remainingCredit-=$creditBorrowed;
            }
        }
    }
}

?>