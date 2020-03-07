package com.entity;

import lombok.Data;
import lombok.EqualsAndHashCode;
import org.hibernate.annotations.DynamicInsert;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Index;
import javax.persistence.Table;

/** @author a.t */
@Entity
@DynamicInsert
@Table(indexes = {@Index(columnList = "symbol,duration,firstAxis,secondAxis",name="twenty_uindex",unique = true)})
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
