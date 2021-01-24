<div class="table-responsive">
    <table class="table">
        <tbody>
            <?php foreach ($orderDetail as $value): ?>
                <tr>
                    <td width="60">
                        <img alt="" width="60" height="60" src="<?php echo $value['product_pic']; ?>">
                    </td>
                    <td><?php echo $value['product_name'].' ('.$value['variant_title'].'-'.$value['variant_selection'].')'; ?></td>
                    <td nowrap><?php echo $value['product_quantity'] .' Adet<br>'; ?></td>
                    <td nowrap><?php echo $value['product_price']. ' ₺'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <table class="table">
        <thead>
            <tr>
                <td>
                    <?php

                        $shippingDetail = json_decode($orderDetail[0]['shipping_address_detail'], true);
                        $billingDetail = json_decode($orderDetail[0]['billing_address_detail'], true);


                        $status = '<span class="badge badge-warning badge-md m-2">Bekliyor</span>';

                        if ($orderDetail[0]['is_approved']) {
                            $status = '<span class="badge badge-success badge-md m-2">Onaylandı</span>';
                        }

                        if ($orderDetail[0]['is_shipped']) {
                            $status .= '<span class="badge badge-info badge-md m-2">Kargoya verildi.</b></span>';
                        }

                        echo $status;
                    
                    ?>
                    
                </td>
                <td nowrap>
                    <?php echo (new DateTime($orderDetail[0]['created_at']))->format('d.m.Y H:i:s'); ?>
                </td>
            </tr>
            <tr>
                <th>Toplam</th>
                <td nowrap><?php echo $orderDetail[0]['order_total_amount']. ' ₺' ?></td>
            </tr>
            <tr>
                <th>Ödeme Yöntemi</th>
                <td nowrap><?php echo $orderDetail[0]['payment_selection'] == 'bank_transfer' ? 'Banka Havalesi' : 'Kredi Kartı'; ?></td>
            </tr>
            <tr>
                <th>Kargo Firması</th>
                <td nowrap><?php echo $orderDetail[0]['cargo_company'];?></td>
            </tr>
            <?php if($orderDetail[0]['cargo_send_code']):?>
            <tr>
                <th>Kargo Gönderim Kodu</th>
                <td nowrap><?php echo $orderDetail[0]['cargo_send_code'];?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <th colspan="2">
                    <h4><strong>Teslimat Bilgileri</strong></h4>
                </th>
            </tr>
            <tr>
                <th>Adresteki Kişi</th>
                <td nowrap><?php echo $shippingDetail['full_name'];?></td>
            </tr>
            <tr>
                <th>İletişim Numarası</th>
                <td nowrap><?php echo $shippingDetail['mobile']; ?></td>
            </tr>
            <tr>
                <th>Adres</th>
                <td nowrap><?php echo $shippingDetail['address'];?></td>
            </tr>
            <tr>
                <th>İlçe</th>
                <td nowrap><?php echo $shippingDetail['county']; ?></td>
            </tr>
            <tr>
                <th>İl</th>
                <td nowrap><?php echo $shippingDetail['city']; ?></td>
            </tr>
            <tr>
                <th>Adres</th>
                <td nowrap><?php echo $shippingDetail['address'];?></td>
            </tr>
            <?php if($billingDetail): ?>
                <tr>
                    <th colspan="2">
                        <h4><strong>Fatura Bilgileri</strong></h4>
                    </th>
                </tr>
                <tr>
                    <th>Kişi</th>
                    <td nowrap><?php echo $billingDetail['full_name'];?></td>
                </tr>
                <tr>
                    <th>İletişim Numarası</th>
                    <td nowrap><?php echo $billingDetail['mobile']; ?></td>
                </tr>
                <tr>
                    <th>Adres</th>
                    <td nowrap><?php echo $billingDetail['address'];?></td>
                </tr>
                <tr>
                    <th>İlçe</th>
                    <td nowrap><?php echo $billingDetail['county']; ?></td>
                </tr>
                <tr>
                    <th>İl</th>
                    <td nowrap><?php echo $billingDetail['city']; ?></td>
                </tr>
                <tr>
                    <th>Adres</th>
                    <td nowrap><?php echo $billingDetail['address'];?></td>
                </tr>
            <?php endif; ?>
        </thead>
    </table>
</div>