<?php

namespace ThePay\ApiClient\Service;

use ThePay\ApiClient\Model\Collection\PaymentMethodCollection;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\Model\IPaymentMethod;
use ThePay\ApiClient\Model\PaymentMethod;
use ThePay\ApiClient\TheConfig;
use ThePay\ApiClient\ValueObject\Identifier;
use ThePay\ApiClient\ValueObject\LanguageCode;

/**
 * Class GateService is responsible for rendering payment forms.
 *
 */
class GateService implements GateServiceInterface
{
    /** @var TheConfig */
    private $config;

    /** @var SignatureService */
    private $signatureService;

    /**
     * @var ApiServiceInterface
     */
    private $api;

    /**
     * GateService constructor.
     * @param TheConfig $config
     * @param ApiServiceInterface $api
     */
    public function __construct(TheConfig $config, ApiServiceInterface $api)
    {
        $this->config = $config;
        $this->api = $api;
        $this->signatureService = new SignatureService($config);
    }

    /**
     * Returns HTML code contains link with redirection to paygate
     * @param string $content HTML content of button
     * @param CreatePaymentParams $params
     * @param string|PaymentMethod|null $methodCode
     * @param array<string, string> $attributes
     * @param bool $usePostMethod
     * @return string HTML
     */
    public function getPaymentButton($content, CreatePaymentParams $params, $methodCode = null, array $attributes = [], $usePostMethod = true)
    {
        $result = '';

        $paymentData = $this->getPaymentData($params);

        if ($usePostMethod) {
            $formId = $this->generateFormId();
            $result .= $this->buildPaymentDataForm($paymentData, ['id' => $formId]);
            // Add data-form-id attribute to send form on link click
            $attributes['data-form-id'] = $formId;
        }

        if ($methodCode instanceof PaymentMethod) {
            $methodCode = $methodCode->getCode();
        }

        if ($methodCode) {
            $attributes['data-payment-method'] = $methodCode;
        }

        $result .= $this->buildButton($this->getUrlForPayment($paymentData, $methodCode), $content, $attributes);

        return $result;
    }

    /**
     * Returns HTML code contains links for each method with redirection to paygate
     * @param CreatePaymentParams     $params
     * @param PaymentMethodCollection $methods
     * @param bool $usePostMethod
     * @return string HTML
     */
    public function getPaymentButtons(CreatePaymentParams $params, PaymentMethodCollection $methods, $usePostMethod = true)
    {
        $paymentData = $this->getPaymentData($params);
        $result = '';

        $btnAttrs = [];

        if ($usePostMethod) {
            $formId = $this->generateFormId();
            $result .= $this->buildPaymentDataForm($paymentData, ['id' => $formId]);
            // Add data-form-id attribute to send form on link click
            $btnAttrs['data-form-id'] = $formId;
        }

        $result .= '<div class="tp-btn-grid" >';
        /** @var IPaymentMethod $method */
        foreach ($methods as $method) {
            $btnAttrs['data-payment-method'] = $method->getCode();
            $result .= $this->buildButton($this->getUrlForPayment($paymentData, $method->getCode()), $this->getButtonMethodContent($method), $btnAttrs);
        }
        $result .= '</div>';

        return $result;
    }

    /**
     * @param Identifier $uid UID of payment
     * @return string HTML
     */
    public function getPaymentButtonsForPayment(Identifier $uid, LanguageCode $languageCode = null)
    {
        $paymentMethods = $this->api->getPaymentUrlsForPayment($uid, $languageCode);
        $result = '';

        $btnAttrs = [];

        $result .= '<div class="tp-btn-grid" >';
        foreach ($paymentMethods as $method) {
            $btnAttrs['data-payment-method'] = $method->getCode();
            $result .= $this->buildButton($method->getPayUrl(), $this->getButtonMethodContent($method), $btnAttrs);
        }
        $result .= '</div>';

        return $result;
    }


    public function getInlineAssets()
    {
        return $this->getInlineStyles()
            . $this->getInlineScripts();
    }

    public function getInlineStyles()
    {
        $filepath = dirname(__FILE__) . '/../../assets/dist/thepay.css';
        return '<style type="text/css" >' . file_get_contents($filepath) . '</style>';
    }

    public function getInlineScripts()
    {
        $filepath = dirname(__FILE__) . '/../../assets/dist/thepay.js';
        return '<script type="text/javascript" >' . file_get_contents($filepath) . '</script>';
    }

    /**
     * Returns link for payment
     * @param array<string, mixed> $paymentData
     * @param string|null $methodCode
     * @return string
     */
    private function getUrlForPayment(array $paymentData, $methodCode = null)
    {
        if ($methodCode) {
            $paymentData['payment_method_code'] = (string) $methodCode;
        }
        return $this->config->getGateUrl() . '?' . http_build_query($paymentData);
    }

    /**
     * @param CreatePaymentParams $params
     * @return array<string, string>
     */
    private function getPaymentData(CreatePaymentParams $params)
    {
        $signed = $this
            ->signatureService
            ->getSignedDataForGate($params);

        $data = [
            'data' => $signed->getData(),
            'signature' => $signed->getSignature(),
        ];

        return $data;
    }

    /**
     * Builds and returns HTML button link to payment
     * @param string $link
     * @param string $content
     * @param array<string, string> $attributes
     * @return string HTML
     */
    private function buildButton($link, $content, array $attributes = [])
    {
        $attributes['href'] = $link;
        $attributes['data-thepay'] = 'payment-button';
        $attributes['class'] = 'tp-btn' . (isset($attributes['class']) ? ' ' . $attributes['class'] : '');

        return '<a ' . $this->htmlAttributes($attributes) . '>' . $content . '</a>';
    }

    /**
     * @return string
     */
    private function generateFormId()
    {
        return str_replace('.', '-', uniqid('thepay-payment-form-', true));
    }

    /**
     * Builds and returns HTML button link to payment
     * @param array<string, mixed> $paymentData
     * @param array<string, string> $attributes
     * @return string HTML
     */
    private function buildPaymentDataForm(array $paymentData, array $attributes = [])
    {
        $attributes = array_merge([
            'action' => $this->config->getGateUrl(),
            'method' => 'post',
        ], $attributes);
        $result = '<form ' . $this->htmlAttributes($attributes) . ' >';
        foreach ($paymentData as $key => $value) {
            $result .= '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '" >';
        }
        $result .= '</form>';

        return $result;
    }

    /**
     * Returns content of method for button link
     * @param IPaymentMethod $method
     * @return string HTML
     */
    private function getButtonMethodContent(IPaymentMethod $method)
    {
        return '<span class="tp-icon" >'
            . '<img ' . $this->htmlAttributes(['src' => $method->getImageUrl(), 'alt' => $method->getTitle()]) . ' />'
            . '</span>'
            . '<span class="tp-title" role="note" >'
            . htmlspecialchars($method->getTitle())
            . '</span>';
    }

    /**
     * @param array<string, string> $attributes
     * @return string
     */
    private function htmlAttributes($attributes)
    {
        $result = '';
        foreach ($attributes as $attrName => $value) {
            if ($result !== '') {
                $result .= ' ';
            }
            $result .= htmlspecialchars($attrName) . '="' . htmlspecialchars($value) . '"';
        }
        return $result;
    }
}
