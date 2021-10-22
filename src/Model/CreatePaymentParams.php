<?php

namespace ThePay\ApiClient\Model;

use ThePay\ApiClient\ValueObject\Amount;
use ThePay\ApiClient\ValueObject\CurrencyCode;
use ThePay\ApiClient\ValueObject\Identifier;
use ThePay\ApiClient\ValueObject\LanguageCode;
use ThePay\ApiClient\ValueObject\Url;

final class CreatePaymentParams
{
    /** @var Amount */
    private $amount;

    /** @var CurrencyCode */
    private $currencyCode;

    /** @var Identifier */
    private $uid;

    /** @var Identifier|null */
    private $orderId;

    /** @var string|null */
    private $descriptionForCustomer;

    /** @var string|null */
    private $descriptionForMerchant;

    /** @var Url|null */
    private $returnUrl;

    /** @var Url|null */
    private $notifUrl;

    /** @var \DateTime|null */
    private $validTo;

    /** @var LanguageCode|null */
    private $languageCode;

    /** @var CreatePaymentCustomer|null */
    private $customer;

    /** @var Subscription|null */
    private $subscription;

    /** @var CreatePaymentItem[] */
    private $items;

    /** @var bool if set to true, then it will remember customer card information for following child payments */
    private $saveAuthorization = false;

    /** @var bool */
    private $isRecurring;

    /** @var bool set to false for pre-authorization */
    private $isDeposit;

    /** @var bool */
    private $canCustomerChangeMethod = true;

    /**
     * CreatePaymentParams constructor.
     *
     * @param int $amount - payment amount in cents
     * @param string $currencyCode - 3 letter UPPERCASE currency code
     * @param string $uid
     * @param string $languageCode - 2 letter lowercase language code
     */
    public function __construct($amount, $currencyCode, $uid, $languageCode = null)
    {
        $this->amount = new Amount($amount);
        $this->currencyCode = new CurrencyCode($currencyCode);
        $this->uid = new Identifier($uid);
        $this->customer = null;
        if ($languageCode) {
            $this->languageCode = LanguageCode::create($languageCode);
        }
        $this->isRecurring = false;
        $this->isDeposit = true;
    }

    /**
     * @return Amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return CurrencyCode
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * @return Identifier
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return CreatePaymentCustomer|null
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @return self
     */
    public function setCustomer(CreatePaymentCustomer $customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return Identifier|null
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param string $orderId
     *
     * @return CreatePaymentParams
     */
    public function setOrderId($orderId)
    {
        $this->orderId = new Identifier($orderId);

        return $this;
    }

    /**
     * @return Subscription|null
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * @param Subscription|null $subscription
     *
     * @return CreatePaymentParams
     */
    public function setSubscription(Subscription $subscription = null)
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescriptionForCustomer()
    {
        return $this->descriptionForCustomer;
    }

    /**
     * @param string $descriptionForCustomer
     *
     * @return CreatePaymentParams
     */
    public function setDescriptionForCustomer($descriptionForCustomer)
    {
        $this->descriptionForCustomer = $descriptionForCustomer;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescriptionForMerchant()
    {
        return $this->descriptionForMerchant;
    }

    /**
     * @param string $descriptionForMerchant
     *
     * @return CreatePaymentParams
     */
    public function setDescriptionForMerchant($descriptionForMerchant)
    {
        $this->descriptionForMerchant = $descriptionForMerchant;
        return $this;
    }

    /**
     * @return Url|null
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * @param string $returnUrl
     *
     * @return CreatePaymentParams
     */
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = new Url($returnUrl);
        return $this;
    }

    /**
     * @return Url|null
     */
    public function getNotifUrl()
    {
        return $this->notifUrl;
    }

    /**
     * @param string $notifUrl
     *
     * @return CreatePaymentParams
     */
    public function setNotifUrl($notifUrl)
    {
        $this->notifUrl = new Url($notifUrl);
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getValidTo()
    {
        return $this->validTo;
    }

    /**
     * @param \DateTime $validTo
     *
     * @return CreatePaymentParams
     */
    public function setValidTo(\DateTime $validTo)
    {
        $this->validTo = $validTo;
        return $this;
    }

    /**
    * @return bool
    */
    public function getSaveAuthorization()
    {
        return $this->saveAuthorization;
    }

    /**
     * @param bool $saveAuthorization
     *
     * @return CreatePaymentParams
     */
    public function setSaveAuthorization($saveAuthorization)
    {
        $this->saveAuthorization = $saveAuthorization;
        return $this;
    }

    /**
     * @return LanguageCode|null
     */
    public function getLanguageCode()
    {
        return $this->languageCode;
    }

    /**
     * @param string $languageCode
     *
     * @return CreatePaymentParams
     */
    public function setLanguageCode($languageCode)
    {
        $this->languageCode = new LanguageCode($languageCode);
        return $this;
    }

    /**
     * @return string
     */
    public function getQueryParams()
    {
        return http_build_query($this->toArray());
    }

    public function addItem(CreatePaymentItem $item)
    {
        $this->items[] = $item;
    }

    /**
     * @return bool
     */
    public function isRecurring()
    {
        return $this->isRecurring;
    }

    /**
     * @param bool $isRecurring
     */
    public function setIsRecurring($isRecurring)
    {
        $this->isRecurring = $isRecurring;
    }

    /**
     * @return bool
     */
    public function isDeposit()
    {
        return $this->isDeposit;
    }

    /**
     * @param bool $isDeposit
     */
    public function setIsDeposit($isDeposit)
    {
        $this->isDeposit = $isDeposit;
    }

    /**
     * @return bool
     */
    public function canCustomerChangeMethod()
    {
        return $this->canCustomerChangeMethod;
    }

    /**
     * @param bool $canCustomerChangeMethod
     */
    public function setCanCustomerChangeMethod($canCustomerChangeMethod)
    {
        $this->canCustomerChangeMethod = $canCustomerChangeMethod;
    }

    /**
     * @return array The associative array of all parameters for signing the request (interface SignableRequest)
     */
    public function toArray()
    {
        $result = array(
            'amount' => $this->amount->getValue(),
            'currency_code' => $this->currencyCode->getValue(),
            'uid' => $this->uid->getValue(),
            'description_for_customer' => $this->descriptionForCustomer,
            'description_for_merchant' => $this->descriptionForMerchant,
            'items' => null,
        );

        if ($this->items) {
            foreach ($this->items as $item) {
                $result['items'][] = $item->toArray();
            }
        }

        if ($this->orderId) {
            $result['order_id'] = $this->orderId->getValue();
        }
        if ($this->returnUrl) {
            $result['return_url'] = $this->returnUrl->getValue();
        }
        if ($this->notifUrl) {
            $result['notif_url'] = $this->notifUrl->getValue();
        }
        if ($this->validTo) {
            $result['valid_to'] = $this->validTo->format(DATE_RFC3339);
        }
        if ($this->languageCode) {
            $result['language_code'] = $this->languageCode->getValue();
        }
        if ($this->customer) {
            $result['customer'] = array(
                'name' => $this->customer->getName(),
                'surname' => $this->customer->getSurname(),
                'email' => $this->customer->getEmail(),
                'phone' => $this->customer->getPhone(),
            );

            $billingAddress = $this->customer->getBillingAddress();
            if ($billingAddress) {
                $result['customer']['billing_address'] = array(
                    'country_code' => $billingAddress->getCountryCode(),
                    'city' => $billingAddress->getCity(),
                    'zip' => $billingAddress->getZip(),
                    'street' => $billingAddress->getStreet(),
                );
            }
        } else {
            $result['customer'] = null;
        }
        if ($this->subscription) {
            $result['subscription'] = $this->subscription->toArray();
        }

        $result['is_recurring'] = $this->isRecurring();
        $result['is_deposit'] = $this->isDeposit();

        $result['save_authorization'] = $this->getSaveAuthorization();

        $result['can_customer_change_method'] = $this->canCustomerChangeMethod();

        return $result;
    }
}
