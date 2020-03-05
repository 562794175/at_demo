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
@Entity

public class BollingTwenty extends BaseEntity {

    @Column(nullable = false, columnDefinition="text")
    private String lower;

    @Column(nullable = false, columnDefinition="text")
    private String main;

    @Column(nullable = false, columnDefinition="text")
    private String upper;


}
