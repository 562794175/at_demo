package com.entity;

import org.springframework.data.annotation.CreatedDate;
import org.springframework.data.annotation.LastModifiedDate;
import org.springframework.data.jpa.domain.support.AuditingEntityListener;

import javax.persistence.*;
import java.io.Serializable;
import java.security.Timestamp;

/**
 * @author a.t
 */
@MappedSuperclass
@Inheritance(strategy = InheritanceType.TABLE_PER_CLASS)
@EntityListeners(AuditingEntityListener.class)
public class BaseEntity implements Serializable {

    @Id
    @GeneratedValue
    @Column(nullable = false, unique = true)
    private Long id;

    @CreatedDate
    @Column(nullable = false, columnDefinition="timestamp")
    private Timestamp createdAt;

    @LastModifiedDate
    @Column(nullable = false, columnDefinition="timestamp")
    private Timestamp updatedAt;

    @Column(nullable = false)
    private String symbol;

    @Column(nullable = false, columnDefinition="int")
    private Integer duration;

    @Column(nullable = false, columnDefinition="timestamp")
    private Integer firstAxis;

    @Column(nullable = false, columnDefinition="timestamp")
    private Integer secondAxis;

}
