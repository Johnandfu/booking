/**
 * Checkout UI Customizer — attach booking_no to checkout attributes + note.
 *
 * Docs:
 * - https://developer.shopline.com/docs/apps/checkout/checkout-ui-customizer/build-an-extension
 * - https://developer.shopline.com/docs/apps/checkout/checkout-ui-customizer/extension-apis/attributes
 * - https://developer.shopline.com/docs/apps/checkout/checkout-ui-customizer/extension-apis/note
 *
 * Use SHOPLINE CLI to scaffold dependencies (`sl extension create/connect`), then replace src.
 */
// @ts-nocheck — types provided by SHOPLINE CLI scaffold

import { useState } from 'react';

function BookingNoField(props) {
  const { applyAttributeChange, applyNoteChange, i18n, settings, attributes } = props;
  const existing = (attributes || []).find((a) => a?.key === 'booking_no')?.value || '';
  const [bookingNo, setBookingNo] = useState(existing);
  const [status, setStatus] = useState('idle');
  const [message, setMessage] = useState('');

  const title = settings?.title || i18n.translate('bookingTitle');
  const help = settings?.help_text || i18n.translate('bookingHelp');

  async function attach() {
    const no = String(bookingNo || '').trim();
    if (!no) {
      setStatus('err');
      setMessage(i18n.translate('missing'));
      return;
    }

    const attrResult = await applyAttributeChange({
      type: 'updateAttribute',
      key: 'booking_no',
      value: no,
    });
    if (attrResult?.type === 'error') {
      setStatus('err');
      setMessage(attrResult.message || 'attribute error');
      return;
    }

    const noteResult = await applyNoteChange({
      type: 'updateNote',
      note: `booking_no:${no}`,
    });
    if (noteResult?.type === 'error') {
      setStatus('err');
      setMessage(noteResult.message || 'note error');
      return;
    }

    setStatus('ok');
    setMessage(i18n.translate('applied'));
  }

  return (
    <BlockStack spacing="base">
      <Text size="medium" emphasis="bold">
        {title}
      </Text>
      <Text size="small">{help}</Text>
      <TextField
        label={i18n.translate('bookingLabel')}
        value={bookingNo}
        onChange={setBookingNo}
      />
      <Button onPress={attach}>{i18n.translate('apply')}</Button>
      {status === 'ok' ? <Banner status="success" title={message} /> : null}
      {status === 'err' ? <Banner status="critical" title={message} /> : null}
    </BlockStack>
  );
}

render('Checkout::Dynamic::Render', (props) => <BookingNoField {...props} />);
