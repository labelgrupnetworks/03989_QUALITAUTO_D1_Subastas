<table class="table_block block-2 mobile_hide" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
    border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td class="pad" style="padding-bottom:10px;padding-left:60px;padding-right:60px;padding-top:20px;">
            <table
                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; width: 100%; table-layout: fixed; direction: ltr; background-color: transparent; font-family: 'Poppins', Arial, Helvetica, sans-serif; font-weight: 400; color: #101112; text-align: right; letter-spacing: 0px;"
                width="100%">
                <tbody style="vertical-align: top; font-size: 16px; line-height: 1.2; mso-line-height-alt: 19px;">
                    @foreach ($lots as $lot)
                        <tr>
                            <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                                width="50%">
                                	{{ trans("web.emails.lot") . " " . $lot['reference'] }}
                            </td>
                            <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                                width="50%">{{ $lot['award_price_format'] }} €</td>
                        </tr>
                    @endforeach

                    <tr>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">
							{{ trans("web.emails.commission") }}
						</td>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">{{ $commission }} €</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">
							{{ trans("web.emails.iva_commission") }}
						</td>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">{{ $commission_iva }} €</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">
							{{ trans("web.emails.shipping") }}
						</td>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">{{ $shipping_costs }} €</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">
							{{ trans("web.emails.shipping_iva") }}
						</td>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">{{ $shipping_costs_iva }} €</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">
							{{ trans("web.emails.export") }}
						</td>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">{{ $export_license }} €</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">
							{{ trans("web.emails.card_payment") }}
						</td>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">{{ $finance_charge }} €</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%"><strong>
								{{ trans("web.emails.total_invoice") }}
								</strong></td>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%"><strong>{{ $total }} €</strong></td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>

<table class="table_block block-3 desktop_hide" role="presentation"
    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; mso-hide: all; display: none; max-height: 0; overflow: hidden;"
    border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td class="pad" style="padding-bottom:10px;padding-left:20px;padding-right:20px;padding-top:20px;">
            <table
                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; mso-hide: all; display: none; max-height: 0; overflow: hidden; border-collapse: collapse; width: 100%; table-layout: fixed; direction: ltr; background-color: transparent; font-family: 'Poppins', Arial, Helvetica, sans-serif; font-weight: 400; color: #101112; text-align: right; letter-spacing: 0px;"
                width="100%">
                <tbody style="vertical-align: top; font-size: 14px; line-height: 1.2; mso-line-height-alt: 17px;">
                    @foreach ($lots as $lot)
                        <tr>
                            <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                                width="50%">
								{{ trans("web.emails.lot") . " " . $lot['reference'] }}
                            </td>
                            <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                                width="50%">{{ $lot['award_price_format'] }} €</td>
                        </tr>
                    @endforeach

                    <tr>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">
							{{ trans("web.emails.commission") }}
						</td>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">{{ $commission }} €</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">
							{{ trans("web.emails.iva_commission") }}
						</td>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">{{ $commission_iva }} €</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">
							{{ trans("web.emails.shipping") }}
						</td>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">{{ $shipping_costs }} €</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">
							{{ trans("web.emails.shipping_iva") }}
						</td>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">{{ $shipping_costs_iva }} €</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">
							{{ trans("web.emails.export") }}
						</td>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">{{ $export_license }} €</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">
							{{ trans("web.emails.card_payment") }}
						</td>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%">{{ $finance_charge }} €</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%"><strong>{{ trans("web.emails.total_invoice") }}</strong></td>
                        <td style="padding: 10px; word-break: break-word; border-top: 1px solid #90825f; border-right: 1px solid #90825f; border-bottom: 1px solid #90825f; border-left: 1px solid #90825f;"
                            width="50%"><strong>{{ $total }} €</strong></td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
