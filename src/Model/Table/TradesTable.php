<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Trades Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Stocks
 *
 * @method \App\Model\Entity\Trade get($primaryKey, $options = [])
 * @method \App\Model\Entity\Trade newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Trade[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Trade|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Trade patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Trade[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Trade findOrCreate($search, callable $callback = null, $options = [])
 */
class TradesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('trades');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Stocks', [
            'foreignKey' => 'stock_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Files', [
            'foreignKey' => 'file_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->date('scrap_date')
            ->requirePresence('scrap_date', 'create')
            ->notEmpty('scrap_date');

        $validator
            ->dateTime('time')
            ->allowEmpty('time');

        $validator
            ->numeric('price')
            ->allowEmpty('price');

        $validator
            ->integer('quantity')
            ->allowEmpty('quantity');

        $validator
            ->allowEmpty('source');

        $validator
            ->allowEmpty('buyer');

        $validator
            ->allowEmpty('seller');

        $validator
            ->allowEmpty('initiator');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['stock_id'], 'Stocks'));
        $rules->add($rules->existsIn(['file_id'], 'Files'));

        return $rules;
    }
}
