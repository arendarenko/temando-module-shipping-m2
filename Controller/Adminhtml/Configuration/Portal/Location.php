<?php
/**
 * Refer to LICENSE.txt distributed with the Temando Shipping module for notice of license
 */
namespace Temando\Shipping\Controller\Adminhtml\Configuration\Portal;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Temando\Shipping\Model\Config\PortalUrl;

/**
 * Shipping Portal Locations Redirect
 *
 * @package Temando\Shipping\Controller
 * @author  Jason Jewel <jason.jewel@temando.com>
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link    https://www.temando.com/
 */
class Location extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see \Magento\Backend\App\Action::_isAllowed()
     */
    const ADMIN_RESOURCE = 'Temando_Shipping::portal';

    /**
     * @var PortalUrl
     */
    private $portalUrl;

    /**
     * Location constructor.
     *
     * @param Context $context
     * @param PortalUrl $portalUrl
     */
    public function __construct(
        Context $context,
        PortalUrl $portalUrl
    ) {
        $this->portalUrl = $portalUrl;

        parent::__construct($context);
    }

    /**
     * Redirect user to Shipping Portal locations
     *
     * @return ResultInterface
     */
    public function execute()
    {
        try {
            $redirectUrl = $this->portalUrl->getLocationsUrl();
        } catch (LocalizedException $exception) {
            $msg = 'Temando Shipping is not properly configured. Please register an account.';
            $this->messageManager->addWarningMessage(__($msg));

            $redirectUrl = $this->getUrl('adminhtml/system_config/edit', [
                'section' => 'carriers',
                '_fragment' => 'carriers_temando-link',
            ]);
        }

        $redirect = $this->resultRedirectFactory->create();
        $redirect->setUrl($redirectUrl);

        return $redirect;
    }
}
