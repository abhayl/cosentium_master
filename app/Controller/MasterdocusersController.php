<?php
class MasterdocusersController extends AppController {
    
    public $uses = array('MasterdocUser', 'User');
    var $paginate = array();
    
    public function index() {
        $this->Session->delete('editAction');
        $this->set('title_for_layout', __('Master Documents'));
        
        if(CakeSession::read('masterDoc') != NULL){
            CakeSession::delete('masterDoc');
        }
        
        $sortImage = array('name' => Configure::read('DEFAULT_SORT_ARROW'), 'owner' => Configure::read('DEFAULT_SORT_ARROW'), 'available_after_dt' => Configure::read('DEFAULT_SORT_ARROW'), 'available_until_dt' => Configure::read('DEFAULT_SORT_ARROW'));
		if (!empty($this->params['named']['direction'])) {
			if ($this->params['named']['direction'] == 'asc') {
				$sortImage[$this->params['named']['sort']] = Configure::read('DEFAULT_SORT_ARROW_TOGGLE');
			} else {
				$sortImage[$this->params['named']['sort']] = Configure::read('DEFAULT_SORT_ARROW');
			}
		}
        $this->set('sortImage', $sortImage);
        
        if($this->Session->read('filterParameter') != ''){
            $this->set('filterSelectedOption', $this->Session->read('filterParameter'));
        }else{
            $this->set('filterSelectedOption', Configure::read('DEFAULT_FILTER'));
			$this->Session->write('filterParameter', Configure::read('DEFAULT_FILTER'));
        }
        
        $this->set('filterSelectedOption', Configure::read('DEFAULT_FILTER'));
        $this->set('paginationSelectedOption', Configure::read('PAGINATION_LIMIT'));
        
        if($this->request->is('post')){
            $this->Session->write('masterdocpageNumber', $this->request->data['MasterdocUser']['records_per_page']);
             $this->Session->write('filterParameter', $this->request->data['MasterdocUser']['master_doc_filter']);
            $this->pageNumber = $this->request->data['MasterdocUser']['records_per_page'];
            $this->paginate['limit'] = $this->request->data['MasterdocUser']['records_per_page'];
        } elseif ($this->Session->read('masterdocpageNumber') != '') {
            $this->paginate['limit'] = $this->Session->read('masterdocpageNumber');
            $this->set('paginationSelectedOption', $this->Session->read('masterdocpageNumber'));
        } else {
            $this->paginate['limit'] = Configure::read('PAGINATION_LIMIT');
        }
        $this->set('padinationDd', Configure::read('PAGINATION_DROPDOWN'));
        $this->set('filterDd', Configure::read('MASTER_DOC_FILTER_DROPDOWN'));
        
        $this->paginate = array(
            'group' => array('MasterdocUser.id'),
            'limit' => $this->paginate['limit'],
            'conditions' => $this->MasterdocUser->getMasterdocCondition($this->Session->read('filterParameter'))
        );
        $this->set('masterDoc', $this->paginate());
    }
}
?>
