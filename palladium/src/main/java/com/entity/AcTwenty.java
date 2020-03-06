package com.entity;

import lombok.Data;
import lombok.EqualsAndHashCode;
import org.hibernate.annotations.DynamicInsert;

import javax.persistence.Column;
import javax.persistence.Entity;

/** @author a.t */
@Entity
@DynamicInsert
public class AcTwenty extends BaseEntity {

  @Column(nullable = false, columnDefinition = "text")
  private String value;

  public String getValue() {
    return value;
  }

  public void setValue(String value) {
    this.value = value;
  }
}
