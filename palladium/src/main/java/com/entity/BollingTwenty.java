package com.entity;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Index;
import javax.persistence.Table;

/** @author a.t */
@Entity
@Table(indexes = {@Index(columnList = "symbol,duration,firstAxis,secondAxis",name="twenty_uindex",unique = true)})
public class BollingTwenty extends BaseEntity {

  @Column(nullable = false, columnDefinition = "text")
  private String lower;

  @Column(nullable = false, columnDefinition = "text")
  private String main;

  @Column(nullable = false, columnDefinition = "text")
  private String upper;

  public String getLower() {
    return lower;
  }

  public void setLower(String lower) {
    this.lower = lower;
  }

  public String getMain() {
    return main;
  }

  public void setMain(String main) {
    this.main = main;
  }

  public String getUpper() {
    return upper;
  }

  public void setUpper(String upper) {
    this.upper = upper;
  }
}
