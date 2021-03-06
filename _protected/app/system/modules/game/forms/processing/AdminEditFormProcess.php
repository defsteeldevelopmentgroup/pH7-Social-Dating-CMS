<?php
/**
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2012-2015, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Module / Game / Form / Processing
 */
namespace PH7;
defined('PH7') or die('Restricted access');

use PH7\Framework\Url\Header, PH7\Framework\Mvc\Router\Uri;

class AdminEditFormProcess extends Form
{

    public function __construct()
    {
        parent::__construct();

        $aData = [
            'id' => $this->httpRequest->get('id', 'int'),
            'category_id' => $this->httpRequest->post('category_id', 'int'),
            'name' => $this->httpRequest->post('name'),
            'title' => $this->httpRequest->post('title'),
            'description' => $this->httpRequest->post('description'),
            'keywords' => $this->httpRequest->post('keywords'),
        ];

        (new GameModel)->update($aData);

        /* Clean GameModel Cache */
        (new Framework\Cache\Cache)->start(GameModel::CACHE_GROUP, null, null)->clear();

         Header::redirect(Uri::get('game', 'main', 'index'), t('The game has been updated successfully!'));

    }

}
