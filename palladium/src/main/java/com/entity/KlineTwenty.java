package com.entity;

import org.springframework.data.annotation.CreatedDate;
import org.springframework.data.annotation.LastModifiedDate;
import org.springframework.data.jpa.domain.support.AuditingEntityListener;

import javax.persistence.*;
import java.security.Timestamp;

/**
 * @author a.t
 */
@Entity
public class KlineTwenty extends BaseEntity {

    @Column(nullable = false, columnDefinition="text")
    private String high;

    @Column(nullable = false, columnDefinition="text")
    private String open;

    @Column(nullable = false, columnDefinition="text")
    private String close;

    @Column(nullable = false, columnDefinition="text")
    private String low;
}
