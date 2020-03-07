package com.entity;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Index;
import javax.persistence.Table;

/** @author a.t */
@Entity
@Table(indexes = {@Index(columnList = "symbol,duration,firstAxis,secondAxis",name="twenty_uindex",unique = true)})
public class KlineTwenty extends BaseEntity {

  @Column(nullable = false, columnDefinition = "text")
  private String high;

  @Column(nullable = false, columnDefinition = "text")
  private String open;

  @Column(nullable = false, columnDefinition = "text")
  private String close;

  @Column(nullable = false, columnDefinition = "text")
  private String low;

  public String getHigh() {
    return high;
  }

  public void setHigh(String high) {
    this.high = high;
  }

  public String getOpen() {
    return open;
  }

  public void setOpen(String open) {
    this.open = open;
  }

  public String getClose() {
    return close;
  }

  public void setClose(String close) {
    this.close = close;
  }

  public String getLow() {
    return low;
  }

  public void setLow(String low) {
    this.low = low;
  }
}
