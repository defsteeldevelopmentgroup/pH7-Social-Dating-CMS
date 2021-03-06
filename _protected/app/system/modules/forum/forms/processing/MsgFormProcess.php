<?php
/**
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2012-2015, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Module / Forum / Form / Processing
 */
namespace PH7;
defined('PH7') or exit('Restricted access');

use
PH7\Framework\Mvc\Model\Engine\Db,
PH7\Framework\Mvc\Model\DbConfig,
PH7\Framework\Mvc\Request\Http,
PH7\Framework\Mvc\Router\Uri,
PH7\Framework\Url\Header;

class MsgFormProcess extends Form
{

    public function __construct()
    {
        parent::__construct();

        $oForumModel = new ForumModel;

        $sMessage = $this->httpRequest->post('message', Http::ONLY_XSS_CLEAN);
        $sCurrentTime = $this->dateTime->get()->dateTime('Y-m-d H:i:s');
        $iTimeDelay = (int) DbConfig::getSetting('timeDelaySendForumTopic');
        $iProfileId = (int) $this->session->get('member_id');
        $iForumId = $this->httpRequest->get('forum_id', 'int');

        if (!$oForumModel->checkWaitTopic($iProfileId, $iTimeDelay, $sCurrentTime))
        {
            \PFBC\Form::setError('form_msg', Form::waitWriteMsg($iTimeDelay));
        }
        elseif ($oForumModel->isDuplicateTopic($iProfileId, $sMessage))
        {
            \PFBC\Form::setError('form_msg', Form::duplicateContentMsg());
        }
        else
        {
            $oForumModel->addTopic($iProfileId, $iForumId, $this->httpRequest->post('title'), $sMessage, $sCurrentTime);
            Header::redirect(Uri::get('forum', 'forum', 'post', $this->httpRequest->get('forum_name').','.$iForumId.','.$this->httpRequest->post('title').','.Db::getInstance()->lastInsertId()), t('Your message has been added successfully!'));
        }
        unset($oForumModel);
    }

}
