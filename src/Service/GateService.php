<?php

namespace ThePay\ApiClient\Service;

use ThePay\ApiClient\Model\Collection\PaymentMethodCollection;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\Model\PaymentMethod;
use ThePay\ApiClient\TheConfig;

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

    /** @var ApiServiceInterface */
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
     * @param PaymentMethod|null $method
     * @param array $attributes
     * @param bool $usePostMethod
     * @return string HTML
     */
    public function getPaymentButton($content, CreatePaymentParams $params, PaymentMethod $method = null, array $attributes = array(), $usePostMethod = true)
    {
        $result = '';

        $paymentData = $this->getPaymentData($params);
        $btnAttrs = array();

        if ($usePostMethod) {
            $formId = $this->generateFormId();
            $result .= $this->buildPaymentDataForm($paymentData, array('id' => $formId));
            // Add data-form-id attribute to send form on link click
            $btnAttrs['data-form-id'] = $formId;
        }

        if ($method) {
            $btnAttrs['data-payment-method'] = $method->getCode();
        }

        $result .= $this->buildButton($this->getUrlForPayment($paymentData, $method), $content, $btnAttrs);

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

        $btnAttrs = array();

        if ($usePostMethod) {
            $formId = $this->generateFormId();
            $result .= $this->buildPaymentDataForm($paymentData, array('id' => $formId));
            // Add data-form-id attribute to send form on link click
            $btnAttrs['data-form-id'] = $formId;
        }

        $result .= '<div class="tp-btn-grid" >';
        foreach ($methods as $method) {
            $btnAttrs['data-payment-method'] = $method->getCode();
            $result .= $this->buildButton($this->getUrlForPayment($paymentData, $method), $this->getButtonMethodContent($method), $btnAttrs);
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
     * @param array $paymentData
     * @param PaymentMethod|null $method
     * @return string
     */
    private function getUrlForPayment(array $paymentData, PaymentMethod $method = null)
    {
        if ($method) {
            $paymentData['payment_method_code'] = $method->getCode();
        }
        return $this->config->getGateUrl() . '?' . http_build_query($paymentData);
    }

    /**
     * @param CreatePaymentParams $params
     * @return array
     */
    private function getPaymentData(CreatePaymentParams $params)
    {
        $signed = $this
            ->signatureService
            ->getSignedDataForGate($params);

        $data = array(
            'data' => $signed->getData(),
            'signature' => $signed->getSignature(),
        );

        return $data;
    }

    /**
     * Builds and returns HTML button link to payment
     * @param string $link
     * @param string $content
     * @param array $attributes
     * @return string HTML
     */
    private function buildButton($link, $content, array $attributes = array())
    {
        $attributes['href'] = $link;
        $attributes['data-thepay'] = 'payment-button';
        $attributes['class'] = 'tp-btn';

        return '<a ' . $this->htmlAttributes($attributes) . '>' . $content . '</a>';
    }

    private function generateFormId()
    {
        return str_replace('.', '-', uniqid('thepay-payment-form-', true));
    }

    /**
     * Builds and returns HTML button link to payment
     * @param array $paymentData
     * @param array $attributes
     * @return string HTML
     */
    private function buildPaymentDataForm(array $paymentData, array $attributes = array())
    {
        $attributes = array_merge(array(
            'action' => $this->config->getGateUrl(),
            'method' => 'post',
        ), $attributes);
        $result = '<form ' . $this->htmlAttributes($attributes) . ' >';
        foreach ($paymentData as $key => $value) {
            $result .= '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '" >';
        }
        $result .= '</form>';

        return $result;
    }

    /**
     * Returns content of method for button link
     * @param PaymentMethod $method
     * @return string HTML
     */
    private function getButtonMethodContent(PaymentMethod $method)
    {
        return '<span class="tp-icon" >'
            . '<img ' . $this->htmlAttributes(array('src' => $method->getImageUrl(), 'alt' => $method->getTitle())) . ' />'
            . '</span>'
            . '<span class="tp-title" role="note" >'
            . htmlspecialchars($method->getTitle())
            . '</span>';
    }

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
