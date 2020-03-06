package com.entity;

import org.hibernate.annotations.DynamicInsert;

import javax.persistence.Column;
import javax.persistence.Entity;

/** @author a.t */
@Entity
@DynamicInsert
public class Quote extends BaseEntity {

  @Column(nullable = false, columnDefinition = "text")
  private String ask;

  @Column(nullable = false, columnDefinition = "text")
  private String bid;

  @Column(nullable = false, columnDefinition = "int")
  private Integer autoStopLoss;

  public String getAsk() {
    return ask;
  }

  public void setAsk(String ask) {
    this.ask = ask;
  }

  public String getBid() {
    return bid;
  }

  public void setBid(String bid) {
    this.bid = bid;
  }

  public Integer getAutoStopLoss() {
    return autoStopLoss;
  }

  public void setAutoStopLoss(Integer autoStopLoss) {
    this.autoStopLoss = autoStopLoss;
  }
}
