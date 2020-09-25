<?php
namespace RookieSoft\CustomerTags\Controller\Adminhtml\TagRule\Rule;

class Save extends \RookieSoft\CustomerTags\Controller\Adminhtml\TagRule\Rule
{

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter
     * @param \Vendor\Rules\Model\RuleFactory $ruleFactory
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
        \RookieSoft\CustomerTags\Model\TagRuleFactory $ruleFactory,
        \Psr\Log\LoggerInterface $logger
    ) {

        parent::__construct($context, $coreRegistry, $fileFactory, $dateFilter, $ruleFactory, $logger);
    }

    /**
     * Rule save action
     *
     * @return void
     */
    public function execute()
    {
        // echo '<pre>';
        // var_dump($this->getRequest()->getPostValue());
        // exit();
        if (!$this->getRequest()->getPostValue()) {
            $this->_redirect('tagrule/*/');
        }

        try {
            /** @var $model \Vendor\Rules\Model\Rule */

            $model = $this->ruleFactory->create();
            $this->_eventManager->dispatch(
                'adminhtml_controller_tagrule_prepare_save',
                ['request' => $this->getRequest()]
            );
            //$id = $data['rule_id'];
            $data = $this->getRequest()->getPostValue();
                // echo '<pre>';
                // var_dump($data);
                // exit();
            if (isset($data['rule_id'])) {
                $model->load($data['rule_id'], 'rule_id');
                // echo '<pre>';
                // var_dump("wwwwwwwwww");
                // exit();
            }
            // echo '<pre>';
            //     var_dump("RRRRRRRRRRRRRRRRR");
            //     exit();
            // echo '<pre>';
            // var_dump($this->getRequest()->getParams());
            // exit();
            //$data = $this->getRequest()->getPostValue();
            //$id = 1;
            // echo '<pre>';
            // var_dump("wwwwwwwwww");
            // exit();
            $validateResult = $model->validateData(new \Magento\Framework\DataObject($data));
            if ($validateResult !== true) {
                foreach ($validateResult as $errorMessage) {
                    $this->messageManager->addErrorMessage($errorMessage);
                }
                $this->_session->setPageData($data);
                $this->_redirect('tagrule/*/edit', ['rule_id' => $model->getRuleId()]);
                return;
            }
            // echo '<pre>';
            // var_dump($model->toArray());
            // exit();
            $data = $this->prepareData($data);

            // $model->loadPost($data);
            // if(isset($data['rule_id'])){
            //     $model->setRuleId($data['rule_id']);
                // echo '<pre>';
                // var_dump('rrrrrrrrrrrrrrrrrr');
                // exit();
            // }
            // echo '<pre>';
            // var_dump($data);
            // exit();
            //$tagIds = '';
            //if(isset($data['tag_id'])){
                // $tagIds = implode(',', $data['tag_id']);
            //}
            // $cc = explode(',', $test);
                // echo '<pre>';
                // var_dump($data);
                // exit();
                $model->loadPost($data);
                // echo '<pre>';
                // var_dump("wwwwwwwwww");
                // exit();
                $this->_session->setPageData($model->getData());
                $model->save();
            // echo '<pre>';
            // var_dump('rrrrrrrrrrrrrrrrrr');
            // exit();
            //$model->load($data['rule_id']);
            // echo '<pre>';
            // var_dump('mmmmmmmmmmm');
            // exit();
            // $model->load($data['rule_id'],'rule_id');
            // echo '<pre>';
            // var_dump($model->toArray());
            // exit();
            // $model->delete();
            // echo '<pre>';
            // var_dump('rrrrrrrrrrrrrrrrrr');
            // exit();
            //$model->setData(['rule_name' => 'TTTTTTTTTT']);
            //$this->_session->setPageData($model->getData());
            // echo '<pre>';
            // var_dump($this->_session->setPageData($model->getData())->toArray());
            // exit();
            // echo '<pre>';
            // var_dump('tttttttttttttttt');
            // exit();
            //$model->save();
            // echo '<pre>';
            // var_dump('rrrrrrrrrrrrrrrrrr');
            // exit();
            $this->messageManager->addSuccessMessage(__('You saved the rule.'));
            //$this->_session->setPageData(false);
            // if ($this->getRequest()->getParam('back')) {
            //     error_log(print_r("BBBBBBBBBBBBBBBBBBB", true));
            //     $this->_redirect('tagrule/*/edit', ['id' => $model->getId()]);
            //     return;
            // }

            $this->_redirect('customertags/tagrule_rule/index');
            return;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            error_log(print_r("AAAAAAAAAAAAAAA", true));
            $this->messageManager->addErrorMessage($e->getMessage());
            error_log(print_r("BBBBBBBBBBBBBBBBBBB", true));
            $id = (int)$this->getRequest()->getParam('rule_id');
            if (!empty($id)) {
                error_log(print_r("CCCCCCCCCCCCCCC", true));
                $this->_redirect('tagrule/*/edit', ['id' => $id]);
            } else {
                error_log(print_r("DDDDDDDDDDDDDDDD", true));
                $this->_redirect('tagrule/*/addrule');
            }
            return;
        } catch (\Exception $e) {
            error_log(print_r("eeeeeeeeeeeeeeeeee", true));
            $this->messageManager->addErrorMessage(
                __('Something went wrong while saving the rule data. Please review the error log.')
            );
            error_log(print_r("FFFFFFFFFFFFFF", true));
            $this->logger->critical($e);
            error_log(print_r("GGGGGGGGGGGGGGGGGG", true));
            $data = !empty($data) ? $data : [];
            $this->_session->setPageData($data);
            // echo '<pre>';
            // var_dump($data);
            // //var_dump($model->toArray());
            // exit();
            $id = (int)$this->getRequest()->getParam('rule_id');
            if (!empty($id)) {
                $this->_redirect('customertags/*/edit', ['rule_id' => $data['rule_id']]);
            } else {
                $this->_redirect('customertags/*/edit');
            }
            return;
        }
    }

    /**
     * Prepares specific data
     *
     * @param array $data
     * @return array
     */
    protected function prepareData($data)
    {
            // echo '<pre>';
            // var_dump($data['conditions']);
            // exit();
        if (isset($data['rule']['conditions'])) {
            $data['conditions'] = $data['rule']['conditions'];
            // $data['tag_id'] = implode(',', $data['tag_id']);
            // echo '<pre>';
            // var_dump($data['tag_id']);
            // exit();
        }

        unset($data['rule']);

        return $data;
    }
}