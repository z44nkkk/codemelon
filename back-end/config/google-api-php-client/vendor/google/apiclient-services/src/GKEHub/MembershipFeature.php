<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\GKEHub;

class MembershipFeature extends \Google\Model
{
  /**
   * @var string
   */
  public $createTime;
  /**
   * @var string
   */
  public $deleteTime;
  protected $featureConfigRefType = FeatureConfigRef::class;
  protected $featureConfigRefDataType = '';
  /**
   * @var string[]
   */
  public $labels;
  protected $lifecycleStateType = LifecycleState::class;
  protected $lifecycleStateDataType = '';
  /**
   * @var string
   */
  public $name;
  protected $specType = FeatureSpec::class;
  protected $specDataType = '';
  protected $stateType = FeatureState::class;
  protected $stateDataType = '';
  /**
   * @var string
   */
  public $updateTime;

  /**
   * @param string
   */
  public function setCreateTime($createTime)
  {
    $this->createTime = $createTime;
  }
  /**
   * @return string
   */
  public function getCreateTime()
  {
    return $this->createTime;
  }
  /**
   * @param string
   */
  public function setDeleteTime($deleteTime)
  {
    $this->deleteTime = $deleteTime;
  }
  /**
   * @return string
   */
  public function getDeleteTime()
  {
    return $this->deleteTime;
  }
  /**
   * @param FeatureConfigRef
   */
  public function setFeatureConfigRef(FeatureConfigRef $featureConfigRef)
  {
    $this->featureConfigRef = $featureConfigRef;
  }
  /**
   * @return FeatureConfigRef
   */
  public function getFeatureConfigRef()
  {
    return $this->featureConfigRef;
  }
  /**
   * @param string[]
   */
  public function setLabels($labels)
  {
    $this->labels = $labels;
  }
  /**
   * @return string[]
   */
  public function getLabels()
  {
    return $this->labels;
  }
  /**
   * @param LifecycleState
   */
  public function setLifecycleState(LifecycleState $lifecycleState)
  {
    $this->lifecycleState = $lifecycleState;
  }
  /**
   * @return LifecycleState
   */
  public function getLifecycleState()
  {
    return $this->lifecycleState;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param FeatureSpec
   */
  public function setSpec(FeatureSpec $spec)
  {
    $this->spec = $spec;
  }
  /**
   * @return FeatureSpec
   */
  public function getSpec()
  {
    return $this->spec;
  }
  /**
   * @param FeatureState
   */
  public function setState(FeatureState $state)
  {
    $this->state = $state;
  }
  /**
   * @return FeatureState
   */
  public function getState()
  {
    return $this->state;
  }
  /**
   * @param string
   */
  public function setUpdateTime($updateTime)
  {
    $this->updateTime = $updateTime;
  }
  /**
   * @return string
   */
  public function getUpdateTime()
  {
    return $this->updateTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(MembershipFeature::class, 'Google_Service_GKEHub_MembershipFeature');
