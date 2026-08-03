<?php

namespace ThePay\ApiClient\Service;

use ThePay\ApiClient\Exception\ApiExceptionInterface;
use ThePay\ApiClient\Exception\NotFoundApiException;
use ThePay\ApiClient\Model\IPaymentMethod;
use ThePay\ApiClient\ValueObject\Identifier;
use ThePay\ApiClient\ValueObject\LanguageCode;

/**
 * Class GateService is responsible for rendering payment forms.
 *
 */
class GateService implements GateServiceInterface
{
    /**
     * @var ApiServiceInterface
     */
    private $api;

    /**
     * GateService constructor.
     * @param ApiServiceInterface $api
     */
    public function __construct(ApiServiceInterface $api)
    {
        $this->api = $api;
    }

    /**
     * @param Identifier $uid UID of payment
     *
     * @return string HTML
     *
     * @throws NotFoundApiException|ApiExceptionInterface
     */
    public function getPaymentButtonsForPayment(Identifier $uid, ?LanguageCode $languageCode = null)
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
