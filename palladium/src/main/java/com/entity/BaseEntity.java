package com.entity;

import org.hibernate.annotations.CreationTimestamp;
import org.hibernate.annotations.DynamicInsert;
import org.hibernate.annotations.UpdateTimestamp;
import org.springframework.data.jpa.domain.support.AuditingEntityListener;

import javax.persistence.*;
import java.io.Serializable;
import java.util.Date;

/** @author a.t */
@MappedSuperclass
@DynamicInsert
@Inheritance(strategy = InheritanceType.TABLE_PER_CLASS)
@EntityListeners(AuditingEntityListener.class)
public class BaseEntity implements Serializable {

  @Id
  @GeneratedValue
  @Column(nullable = false, unique = true)
  private Long id;

  @CreationTimestamp
  @Column(nullable = false, columnDefinition = "datetime")
  private Date createdAt;

  @UpdateTimestamp
  @Column(nullable = false, columnDefinition = "datetime")
  private Date updatedAt;

  @Column(nullable = false,length = 10)
  private String symbol;

  @Column(nullable = false, columnDefinition = "int")
  private Integer duration;

  @Column(nullable = false,length = 20)
  private String firstAxis;

  @Column(nullable = false,length = 20)
  private String secondAxis;

  public Long getId() {
    return id;
  }

  public void setId(Long id) {
    this.id = id;
  }

  public Date getCreatedAt() {
    return createdAt;
  }

  public void setCreatedAt(Date createdAt) {
    this.createdAt = createdAt;
  }

  public Date getUpdatedAt() {
    return updatedAt;
  }

  public void setUpdatedAt(Date updatedAt) {
    this.updatedAt = updatedAt;
  }

  public String getSymbol() {
    return symbol;
  }

  public void setSymbol(String symbol) {
    this.symbol = symbol;
  }

  public Integer getDuration() {
    return duration;
  }

  public void setDuration(Integer duration) {
    this.duration = duration;
  }

  public String getFirstAxis() {
    return firstAxis;
  }

  public void setFirstAxis(String firstAxis) {
    this.firstAxis = firstAxis;
  }

  public String getSecondAxis() {
    return secondAxis;
  }

  public void setSecondAxis(String secondAxis) {
    this.secondAxis = secondAxis;
  }
}
