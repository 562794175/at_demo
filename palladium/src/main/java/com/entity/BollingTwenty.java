package com.entity;

import javax.persistence.Column;
import javax.persistence.Entity;

/** @author a.t */
@Entity
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
